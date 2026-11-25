@extends('layouts.admin.app')
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#adminAppointmentTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                lengthMenu: [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]],
                ajax: {
                    url: '{!! route('admin.appointment.appointment.getAppointments') !!}',
                    type: "get",
                    data: function (d) {
                        d.name = $('input[name=name]').val();
                        d.operator = $('select[name=operator]').val();
                        d.post = $('input[name=post]').val();
                    }
                },
                order: [[0, 'desc']],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'appointment_type_name', name: 'appointment_type_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'appointment_date', name: 'appointment_date'},
                    {data: 'appointment_time', name: 'appointment_time'},
                    {data: 'is_completed', name: 'is_completed'},
                    {data: 'actions', name: 'actions'}
                ]
            });
        });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Appointments
                    <span class="d-block text-muted pt-2 font-size-sm">Lists of Appointments</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="adminAppointmentTable">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Is Completed</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Is Completed</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
