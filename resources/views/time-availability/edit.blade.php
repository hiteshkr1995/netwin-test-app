@extends('layouts.app')

@section('title', 'Edit Doctor Time Availability')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="text-end mb-5">
                <a href="{{ route('doctors.index') }}" class="btn btn-success">
                    Home
                </a>
            </div>

            <div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('doctors.update', $doctor->id) }}" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="doctor-name" class="col-2 col-form-label text-end">
                        <strong>Doctor</strong>
                    </label>
                    <div class="col-10">
                        <select id="doctor-name"  class="form-select" required disabled>
                            <option selected>{{ $doctor->name }}</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-2 col-form-label text-end">
                        <strong>Time Availability</strong>
                    </label>
                    <div class="col-10 col-form-label">
                        <div class="row">
                            <div class="col">
                                <strong>Day</strong>
                            </div>
                            <div class="col">
                                <strong>Start Time</strong>
                            </div>
                            <div class="col">
                                <strong>End Time</strong>
                            </div>
                        </div>

                        <hr>

                        @foreach ($days as $key => $day)

                            @php
                                $timeAvailablity = $doctor->timeAvailabilities->where('days', $key)->where('open_status', true)->first();
                                // dump($timeAvailablity->start_time);
                                // dd($timeAvailablity->created_at);
                            @endphp

                            <div class="row my-4">
                                <div class="col">
                                    <div class="form-check">
                                        <input id="check-day-{{ $key }}" class="form-check-input" value="{{ $key }}" type="checkbox" @if (!is_null($timeAvailablity)) checked @endif>
                                        <label class="form-check-label" for="check-day-{{ $key }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <input class="form-control timepicker checked-{{$key}}" name="days[{{$key}}][start_time]" required @if (!is_null($timeAvailablity)) value="{{$timeAvailablity->start_time->format('h:i A')}}" @else disabled @endif>
                                </div>
                                <div class="col">
                                    <input class="form-control timepicker checked-{{$key}}" name="days[{{$key}}][end_time]" required @if (!is_null($timeAvailablity)) value="{{$timeAvailablity->end_time->format('h:i A')}}" @else disabled @endif>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <button type="submit" class="btn btn-success w-50">Update</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endpush

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>
    $(document).ready(function(){
        $('input.timepicker').timepicker({});

        $('.form-check-input').change(function(evt) {
            $(`.checked-${evt.target.value}`).prop('disabled', !evt.target.checked);
        });
    });
</script>
@endpush