<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\TimeAvailability;
use App\Http\Requests\Dcotor\StoreRequest;
use App\Http\Requests\Dcotor\UpdateRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctors = Doctor::whereHas('timeAvailabilities', function (Builder $query) use ($request) {
            $query->when($request->day, function ($query, $day) {
                return $query->where('days', $day)
                ->where('open_status', true);
            });

            if ($request->start_time && $request->end_time) {
                $startEndTime = [
                    Carbon::parse($request->start_time)->format('H:i:s'),
                    Carbon::parse($request->end_time)->format('H:i:s'),
                ];
                $query->whereBetween('start_time', $startEndTime)
                ->whereBetween('end_time', $startEndTime)
                ->when(!$request->day, function ($query, $day) {
                    return $query->where('open_status', true);
                });;
            }
        })->when($request->doctor_name, function ($query, $doctorName) {
            return $query->where('name', 'like', "%{$doctorName}%");
        })->get();

        $days = TimeAvailability::DAYS;

        return view('welcome', compact('doctors', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = Doctor::all();
        $days = TimeAvailability::DAYS;
        return view('time-availability.create', compact('doctors', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        foreach (TimeAvailability::DAYS as $key => $day) {
            $timeAvailability = new TimeAvailability;
            $timeAvailability->doctor_id = $request->doctor_id;
            $timeAvailability->days = $key;
            if(isset($request->days[$key])) {
                $requestDay = $request->days[$key];

                $timeAvailability->open_status = true;
                $timeAvailability->start_time = Carbon::parse($requestDay['start_time'])->format('H:i:s');
                $timeAvailability->end_time = Carbon::parse($requestDay['end_time'])->format('H:i:s');
            }

            $timeAvailability->save();
        }

        return redirect()->route('doctors.index')->with('status', 'Added time availability!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load('timeAvailabilities');
        $days = TimeAvailability::DAYS;

        return view('time-availability.edit', compact('doctor', 'days'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Doctor $doctor)
    {
        foreach ($doctor->timeAvailabilities as $key => $timeAvailability) {
            if(isset($request->days[$timeAvailability->days])) {
                $requestDay = $request->days[$timeAvailability->days];

                $timeAvailability->open_status = true;
                $timeAvailability->start_time = Carbon::parse($requestDay['start_time'])->format('H:i:s');
                $timeAvailability->end_time = Carbon::parse($requestDay['end_time'])->format('H:i:s');
            } else {
                $timeAvailability->open_status = false;
                $timeAvailability->start_time = 0;
                $timeAvailability->end_time = 0;
            }

            $timeAvailability->save();
        }

        return redirect()->route('doctors.index')->with('status', 'Updated time availability!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        request()->session()->flash('status', 'Deleted successful!');
        return $doctor->timeAvailabilities()->delete();
    }
}
