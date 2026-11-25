@extends('layouts.admin.app')
@section('scripts')
    <script>
        $(document).ready(function () {
            {{--$("#defaultDataTable").on("click", ".delete", function () {--}}
            {{--    $object = $(this);--}}
            {{--    var id = $object.attr('id');--}}
            {{--    Swal.fire({--}}
            {{--        title: "Are you sure?",--}}
            {{--        text: "You will not be able to recover this !",--}}
            {{--        icon: "warning",--}}
            {{--        showCancelButton: true,--}}
            {{--        confirmButtonText: "Yes, delete it!",--}}
            {{--        cancelButtonText: "No, cancel!",--}}
            {{--        reverseButtons: true--}}
            {{--    }).then(function (result) {--}}
            {{--        if (result.value) {--}}
            {{--            $.ajax({--}}
            {{--                type: "POST",--}}
            {{--                url: baseUrl + "/appointment/types" + "/" + id,--}}
            {{--                data: {--}}
            {{--                    id: id,--}}
            {{--                    _method: 'DELETE'--}}
            {{--                },--}}
            {{--                success: function (response) {--}}
            {{--                    if (response.status == 'ok') {--}}
            {{--                        Swal.fire("Deleted!", response.message, "success")--}}
            {{--                        var oTable = $('#defaultDataTable').dataTable();--}}
            {{--                        var nRow = $($object).parents('tr')[0];--}}
            {{--                        oTable.fnDeleteRow(nRow);--}}
            {{--                    } else {--}}
            {{--                        Swal.fire("Error!", response.message, "error")--}}
            {{--                    }--}}
            {{--                },--}}
            {{--                error: function (e) {--}}
            {{--                    if (e.responseJSON.message) {--}}
            {{--                        Swal.fire("Error!", e.responseJSON.message, "error")--}}
            {{--                    } else {--}}
            {{--                        Swal.fire("Error!", 'Something went wrong while processing your request.', "error")--}}
            {{--                    }--}}
            {{--                }--}}
            {{--            });--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
            {{--$("#defaultDataTable").on("click", ".change-status", function () {--}}
            {{--    $object = $(this);--}}
            {{--    var id = $object.attr('id');--}}
            {{--    Swal.fire({--}}
            {{--        title: "Are you sure?",--}}
            {{--        text: "Do you want to change the status",--}}
            {{--        icon: "warning",--}}
            {{--        showCancelButton: true,--}}
            {{--        confirmButtonText: "Yes, Change it!",--}}
            {{--        cancelButtonText: "No, cancel!",--}}
            {{--        reverseButtons: true--}}
            {{--    }).then(function (result) {--}}
            {{--        if (result.value) {--}}
            {{--            $.ajax({--}}
            {{--                type: "POST",--}}
            {{--                url: "{{ route('admin.appointment.types.change-status') }}",--}}
            {{--                data: {--}}
            {{--                    id: id--}}
            {{--                },--}}
            {{--                success: function (response) {--}}
            {{--                    if (response.status == 'ok') {--}}
            {{--                        if (response.response.is_active == 1) {--}}
            {{--                            $($object).children().removeClass('fas fa-minus-circle');--}}
            {{--                            $($object).children().addClass('far fa-check-circle');--}}
            {{--                        } else {--}}
            {{--                            $($object).children().removeClass('far fa-check-circle');--}}
            {{--                            $($object).children().addClass('fas fa-minus-circle');--}}
            {{--                        }--}}
            {{--                        Swal.fire("Success!", response.message, "success")--}}
            {{--                    } else {--}}
            {{--                        Swal.fire("Error!", response.message, "error")--}}
            {{--                    }--}}
            {{--                },--}}
            {{--                error: function (e) {--}}
            {{--                    if (e.responseJSON.message) {--}}
            {{--                        Swal.fire("Error!", e.responseJSON.message, "error")--}}
            {{--                    } else {--}}
            {{--                        Swal.fire("Error!", 'Something went wrong while processing your request.', "error")--}}
            {{--                    }--}}
            {{--                }--}}
            {{--            });--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
        });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Appointment Setup
                    <span class="d-block text-muted pt-2 font-size-sm">Lists of Appointment Setup</span>
                </h3>
            </div>
            @if(auth('admin')->user()->can('appointment-create'))
                <div class="card-toolbar">
                    <a href="{{ route('admin.appointment.setup.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>New Setup</a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="defaultDataTable">
                <thead>
                <tr>
                    <th>S. No</th>
                    <th>Appointment Type</th>
                    <th>Day</th>
                    <th>Effective From</th>
                    <th>Effective To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Time Slot</th>
                    <th>Available Slot</th>
                </tr>
                </thead>
                <tbody>
                    <?php $sno = 1; ?>
                @foreach($setups as $index=>$setup)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $setup->appointmentType->name }}</td>
                        <td>{{ $setup->day }}</td>
                        <td>{{ \App\Helpers\Misc::usFormatDate($setup->effective_from) }}</td>
                        <td>{{ \App\Helpers\Misc::usFormatDate($setup->effective_to) }}</td>
                        <td>{{ date("h:i a", strtotime($setup->start_time)) }}</td>
                        <td>{{ date("h:i a", strtotime($setup->end_time)) }}</td>
                        <td>{{ $setup->time_slot }} min</td>
                        <td>{{ $setup->available_slot }}</td>

                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>S. No</th>
                    <th>Appointment Type</th>
                    <th>Day</th>
                    <th>Effective From</th>
                    <th>Effective To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Time Slot</th>
                    <th>Available Slot</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
