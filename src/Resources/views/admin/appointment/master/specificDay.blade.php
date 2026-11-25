@extends('layouts.admin.app')
@section('scripts')
    <script>
        $(document).ready(function () {

            $('#defaultDataTable1').DataTable({
                "paging": false,
                "ordering": false,
                "info": false
            });

            $("#defaultDataTable1").on("click", ".change-status", function () {
                $object = $(this);
                var id = $object.attr('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to change the status",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Change it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.appointment.master-setup.specific-day.change-status') }}",
                            data: {
                                id: id
                            },
                            success: function (response) {
                                if (response.status == 'ok') {
                                    if (response.response.is_active == 1) {
                                        $($object).children().removeClass('fas fa-minus-circle');
                                        $($object).children().addClass('far fa-check-circle');
                                    } else {
                                        $($object).children().removeClass('far fa-check-circle');
                                        $($object).children().addClass('fas fa-minus-circle');
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


            $(".checkedAll").change(function () {
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        this.checked = true;
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                }
            });


            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;

                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                    });

                    if (isAllChecked == 0) {
                        $(".checkedAll").prop("checked", true);
                    }
                } else {
                    $(".checkedAll").prop("checked", false);
                }
            });
        });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Specific Schedules
                    <span class="d-block text-muted pt-2 font-size-sm">{{ \App\Helpers\Misc::usFormatDate($startDate) }} - {{ \App\Helpers\Misc::usFormatDate($endDate) }}</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-header flex-wrap py-3">
                    <div class="" style="padding-top: 0px; padding-bottom: 0px;">
                        <h5 class="card-label" style="padding-top: 7px;">Filter</h5>
                    </div>
                </div>
                <div class="card-body" style="padding-bottom: 0px;">
                    <form class="m-form m-form--fit m-form--label-align-right" method="get">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                {!! Form::label('start_date','Start Date *') !!}
                                {!! Form::date('start_date', $startDate, array('class'=>'form-control','autocomplete'=>'off')) !!}
                            </div>
                            <div class="col-lg-4">
                                {!! Form::label('date','End Date *') !!}
                                {!! Form::date('end_date', $endDate, array('class'=>'form-control','autocomplete'=>'off')) !!}
                            </div>
                            <div class="col-lg-4">
                                {!! Form::label('appointment_type_id','Appointment Type *') !!}
                                {!! Form::select('appointment_type_id', $appointmentTypes,$appointmentTypeId, array('class' => 'form-control selectpicker')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="results pt-10">

                {!! Form::open(array('route' => ['admin.appointment.master-setup.specific-day.bulk-change-status'],'class'=>'m-form m-form--fit')) !!}
                <table class="table table-bordered" id="defaultDataTable1">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="checkedAll" class="checkedAll"/>&nbsp;&nbsp;Select All</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Available Slot</th>
                        <th>Used Slot</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($availableAppointments as $index=>$availableAppointment)
                        <tr>
                            <td style="width: 8%;"><input type="checkbox" value="{{ $availableAppointment->id }}"
                                                          name="appointment_id[]" class="checkSingle"></td>
                            <td>{{ $availableAppointment->type->name }}</td>
                            <td>{{ \App\Helpers\Misc::usFormatDate($availableAppointment->appointment_date) }}</td>
                            <td>{{ date("h:i a", strtotime($availableAppointment->start_time)) }}</td>
                            <td>{{ date("h:i a", strtotime($availableAppointment->end_time)) }}</td>
                            <td>{{ $availableAppointment->available_slot }}</td>
                            <td>{{ $availableAppointment->used_slot }}</td>
                            <td>
                                <a href="javascript:void(0)"
                                   class="btn btn-icon btn-light btn-hover-info btn change-status"
                                   id="{{ $availableAppointment->id }}">
                                    @if($availableAppointment->is_active == 1)
                                        <i class="far fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-minus-circle"></i>
                                    @endif
                                </a>
                            </td>
                            <td>
                                @if(auth('admin')->user()->can('appointment-edit'))
                                    <a href="{{ route('admin.appointment.master-setup.edit',$availableAppointment->id) }}" class="btn btn-icon btn-light btn-hover-success btn" title="Edit"
                                       data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><input type="checkbox" name="checkedAll" class="checkedAll"/>&nbsp;&nbsp;Select All</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Available Slot</th>
                        <th>Used Slot</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-3">
                                {!! Form::label('status','Status *') !!}
                                {!! Form::select('status',array('publish' => 'Publish', 'unpublish' => 'Unpublish'), 'unpublish', array('class'=>'form-control m-bootstrap-select m_selectpicker')) !!}
                            </div>
                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Update Status
                                </button>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                        {!! Form::hidden('start_date', \App\Helpers\Misc::usFormatDate($startDate)) !!}
                        {!! Form::hidden('end_date', \App\Helpers\Misc::usFormatDate($endDate)) !!}
                        {!! Form::hidden('appointment_type_id', $appointmentTypeId) !!}
                    </div>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>
@endsection
