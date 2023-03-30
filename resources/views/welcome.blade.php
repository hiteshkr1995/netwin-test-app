@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="text-end my-5">
        <a href="{{ route('doctors.create') }}" class="btn btn-success">
            {{ __('Add Appointment Time') }}
        </a>
    </div>

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
            @foreach ($doctors as $key => $doctor)
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
            @endforeach
        </tbody>
    </table>

@endsection

@push('styles')
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

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
            console.log('result', result);
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    }
</script>
@endpush