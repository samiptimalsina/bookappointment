@extends('layouts.admin.app')
@section('scripts')
    <script>
        $(document).ready(function () {
            $("#showAppointment").on("click", ".markAsCompleted", function () {
                $object = $(this);
                var id = $object.attr('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to mark as complete",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Change it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.appointment.appointment.completed') }}",
                            data: {
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 'ok') {

                                    if (response.appointment.is_completed == 1) {
                                        $(".markAsCompleted").remove();
                                        $(".cancelAppointment").remove();
                                        $('.appointmentCompleted').html('<span class="badge badge-success">Yes</span>');
                                    }
                                    Swal.fire("Success!", response.message, "success")
                                } else {
                                    Swal.fire("Error!", response.message, "error")
                                }
                            },
                            error: function (e) {
                                if (e.responseJSON.message) {
                                    Swal.fire("Error!", e.responseJSON.message, "error")
                                } else {
                                    Swal.fire("Error!", 'Something went wrong while processing your request.', "error")
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3 showAppointment" id="showAppointment">
            <div class="card-title">
                <h3 class="card-label">Appointment Detail
                    <span class="d-block text-muted pt-2 font-size-sm">View</span>
                </h3>
            </div>
            <div class="card-toolbar">
                @if($appointment->is_completed == 0)
                    <button class="btn btn-success font-weight-bolder mr-5 markAsCompleted "
                            id="{{ $appointment->id }}">
                        <i class="la la-check"></i>Mark As Completed
                    </button>
                    <a href="{{ route('admin.appointment.appointment.cancel',Crypt::encrypt($appointment->id)) }}"
                       class="btn btn-danger font-weight-bolder mr-5 ">
                        <i class="flaticon-cancel"></i>Cancel Appointment
                    </a>
                @endif
                <a href="{{ route('admin.appointment.appointment.index') }}" class="btn btn-primary font-weight-bolder">
                    <i class="la la-backward"></i>Back</a>
            </div>
        </div>
        <div class="card-body">

            <table class="table table-striped- table-bordered table-hover">

                <tbody>
                <tr>
                    <td>Appointment Name</td>
                    <td>{{ $appointment->first_name.' '.$appointment->last_name }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $appointment->phone }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $appointment->email }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $appointment->address }}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>{{ $appointment->zipCode->city->name }}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>{{ $appointment->zipCode->state->name }}</td>
                </tr>
                <tr>
                    <td>Zip</td>
                    <td>{{ $appointment->zipCode->code }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ \App\Helpers\Misc::usFormatDate($appointment->appointment_date) }}</td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>{{ date("h:i a", strtotime($appointment->start_time)).' - '.date("h:i a", strtotime($appointment->end_time)) }}</td>
                </tr>

                <tr>
                    <td>Appointment Type</td>
                    <td>{{ isset($appointment->appointmentSetup->appointmentType) ? $appointment->appointmentSetup->appointmentType->name :  'N/A' }}</td>
                </tr>

                <tr>
                    <td>Is Confirmed</td>
                    <td id="markas">{!! $appointment->is_confirmed ? '<span class="badge badge-success">Yes</span>':'<span class="badge badge-primary">No</span>' !!}</td>
                </tr>
                <tr>
                    <td>Is Completed?</td>
                    <td><p id="appointmentCompleted"
                           class="appointmentCompleted">{!! $appointment->is_completed ? '<span class="badge badge-success">Yes</span>':'<span class="badge badge-primary">No</span>' !!}</p>
                    </td>
                </tr>
                <tr>
                    <td>Message</td>
                    <td>{!! $appointment->message  !!}</td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>
@endsection
