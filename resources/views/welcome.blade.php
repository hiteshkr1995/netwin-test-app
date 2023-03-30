@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="text-end my-5">
        <a href="{{ route('doctors.create') }}" class="btn btn-success">
            {{ __('Add Appointment Time') }}
        </a>
    </div>

    <hr>

    <form method="GET" autocomplete="off">
        <div class="row">

            <div class="col">
                <input type="text" name="doctor_name" value="{{ request()->doctor_name }}" class="form-control" placeholder="Enter name">
            </div>
            <div class="col">
                <select class="form-select" name="day">
                    <option selected value="">Select day</option>
                        @foreach ($days as $key => $day)
                            <option value="{{$key}}" @if (request()->day == $key) selected @endif>{{$day}}</option>
                        @endforeach
                </select>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="text" name="start_time" value="{{ request()->start_time }}" class="form-control timepicker" placeholder="Start time">
                    </div>
                    <div class="col">
                        <input type="text" name="end_time" value="{{ request()->end_time }}" class="form-control timepicker" placeholder="End time">
                    </div>
                </div>
            </div>
            <div class="col text-end">
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>

        </div>
    </form>

    <hr>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Address</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($doctors as $key => $doctor)
                <tr>
                    <th scope="row">{{ ++$key }}</th>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->address }}</td>
                    <td>
                        <a href="{{ route('doctors.edit', $doctor->id) }}" class="edit-button mx-2">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <button type="button" class="delete-button mx-2" onclick="openDeleteConfirm(event, {{ $doctor->id }})">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <th colspan="4" class="text-danger">No record found.</th>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <style>
        .edit-button {
            text-decoration: none;
        }

        .delete-button{
            padding: 0;
            border: none;
            background: none;
        }
    </style>
@endpush

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>

    $('input.timepicker').timepicker({});

    function openDeleteConfirm(event, id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise(function (resolve) {
                   return $.ajax({
                        method: "DELETE",
                        url: `/doctors/${id}`,
                    })
                    .done(function( res ) {
                        // return res;
                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                        Swal.showValidationMessage(
                            `Request failed: ${errorThrown}`
                        );
                    }).always(function( jqXHR, textStatus, errorThrown ) {
                        resolve();
                    });
                });
            },
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        })
    }
</script>
@endpush