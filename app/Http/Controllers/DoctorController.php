<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\TimeAvailability;
use App\Http\Requests\Dcotor\StoreRequest;
use App\Http\Requests\Dcotor\UpdateRequest;
use Carbon\Carbon;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::all();

        return view('welcome', compact('doctors'));
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

                $timeAvailability->open_status =  true;
                $timeAvailability->start_time =  Carbon::parse($requestDay['start_time'])->format('H:i:s');
                $timeAvailability->end_time =  Carbon::parse($requestDay['end_time'])->format('H:i:s');
            }

            $timeAvailability->save();
        }

        return redirect()->route('doctors.index');
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
        return view('time-availability.edit');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
