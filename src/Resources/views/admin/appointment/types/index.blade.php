@extends('layouts.admin.app')
@section('scripts')
    <script>
        $(document).ready(function () {
            $("#defaultDataTable").on("click", ".delete", function () {
                $object = $(this);
                var id = $object.attr('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "/appointment/types" + "/" + id,
                            data: {
                                id: id,
                                _method: 'DELETE'
                            },
                            success: function (response) {
                                if (response.status == 'ok') {
                                    Swal.fire("Deleted!", response.message, "success")
                                    var oTable = $('#defaultDataTable').dataTable();
                                    var nRow = $($object).parents('tr')[0];
                                    oTable.fnDeleteRow(nRow);
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
            $("#defaultDataTable").on("click", ".change-status", function () {
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
                            url: "{{ route('admin.appointment.types.change-status') }}",
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
            $('table tbody').sortable({
                update: function (event, ui) {
                    var $object = $(this);
                    var contents = $(this).sortable('serialize');
                    var count = parseInt($object.children().first().children('td:nth-child(2)').html());
                    $object.children('tr').each(function () {
                        var sn = parseInt($(this).children('td:nth-child(2)').html());
                        if (sn < count) {
                            count = sn;
                        }
                    });
                    $object.children('tr').each(function () {
                        $(this).children('td:nth-child(2)').html(count);
                        count++;
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.appointment.types.sort') }}",
                        data: {orderings: contents},
                        success: function (response) {
                            Swal.fire("Success!", response.message, "success")
                        },
                        error: function (e) {
                            if (e.responseJSON.message) {
                                Swal('Error', e.responseJSON.message, 'error');
                            } else {
                                Swal.fire("Error!", 'Something went wrong while processing your request.', "error")
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Appointment Type
                    <span class="d-block text-muted pt-2 font-size-sm">Lists of Appointment Type</span>
                </h3>
            </div>
            @if(auth('admin')->user()->can('appointment-create'))
                <div class="card-toolbar">
                    <a href="{{ route('admin.appointment.types.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>New Type</a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="defaultDataTable">
                <thead>
                <tr>
                    <th></th>
                    <th>S. No</th>
                    <th>Name</th>
                    <th>Created at</th>
                    <th>Publish</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($types as $index=>$type)
                    <tr id="order_{{ $type->id }}">
                        <td class="text-center"><i class="fas fa-arrows-alt"></i></td>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $type->name }}</td>
                        <td>{{ \App\Helpers\Misc::usFormatDateTime($type->created_at) }}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-icon btn-light btn-hover-info btn change-status"
                               id="{{ $type->id }}">
                                @if($type->is_active == 1)
                                    <i class="far fa-check-circle"></i>
                                @else
                                    <i class="fas fa-minus-circle"></i>
                                @endif
                            </a>

                        </td>
                        <td nowrap="nowrap" class="text-right">
                            @if(auth('admin')->user()->can('appointment-edit'))
                                <a href="{{ route('admin.appointment.types.edit',$type->id) }}"
                                   class="btn btn-icon btn-light btn-hover-success btn" title="Edit Appointment Type"
                                   data-toggle="tooltip"><i
                                        class="fas fa-edit"></i></a>
                            @endcan

                            @if(auth('admin')->user()->can('appointment-delete'))
                                <a href="#" id="{{ $type->id }}"
                                   class="btn btn-icon btn-light btn-hover-danger btn delete"
                                   title="Delete Appointment Type"
                                   data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
