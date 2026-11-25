@extends('layouts.admin.app')
@section('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function (e) {
        //     FormValidation.formValidation(
        //         document.getElementById('wsValidator'),
        //         {
        //             fields: {
        //                 "question": {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'Question is required'
        //                         }
        //                     }
        //                 },
        //                 "answer": {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'Answer is required'
        //                         }
        //                     }
        //                 },
        //             },
        //             plugins: {
        //                 trigger: new FormValidation.plugins.Trigger(),
        //                 bootstrap: new FormValidation.plugins.Bootstrap(),
        //                 submitButton: new FormValidation.plugins.SubmitButton(),
        //                 defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        //             }
        //         }
        //     );
        // });
    </script>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Appointment Setup
                    <span class="d-block text-muted pt-2 font-size-sm">New Appointment Setup</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.appointment.setup.index') }}" class="btn btn-primary font-weight-bolder">
                    <i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
        {{ Form::open(array('route' => 'admin.appointment.setup.store','files'=>true,'id'=>'wsValidator')) }}
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-6">
                    {!! Form::label('appointment_type_id','Appointment Type *') !!}
                    {!! Form::select('appointment_type_id', $appointmentTypes,'', array('class' => 'form-control selectpicker')) !!}
                </div>
                <div class="col-lg-6">
                    {!! Form::label('applicable_days[]','Applicable For *') !!}
                    {!! Form::select('applicable_days[]', [
                     'Monday' => 'Monday',
                     'Tuesday' => 'Tuesday',
                     'Wednesday' => 'Wednesday',
                     'Thursday' => 'Thursday',
                     'Friday' => 'Friday',
                     'Saturday' => 'Saturday',
                      'Sunday' => 'Sunday',
                     ], null,array('class' => 'form-control selectpicker','multiple'=>true)) !!}

                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-6">
                    {!! Form::label('appointment_type_id','Start & End Date *') !!}
                    {!! Form::text('appointment_date', null, array('class'=>'form-control m-input','autocomplete'=>'off','id'=>'kt_daterangepicker_1','readonly'=>true)) !!}
                </div>
                <div class="col-lg-3">
                    {!! Form::label('start_time','Start Time *') !!}
                    {!! Form::text('start_time', null, array('class'=>'form-control','id'=>'kt_timepicker_1','autocomplete'=>'off','readonly'=>true)) !!}
                </div>
                <div class="col-lg-3">
                    {!! Form::label('start_time','End Time *') !!}
                    {!! Form::text('end_time', null, array('class'=>'form-control','id'=>'kt_timepicker_1','autocomplete'=>'off')) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-6">
                    {!! Form::label('time_slot','Time Slot Interval *') !!}
                    {!! Form::select('time_slot', [
                    '30' => '30 Min.',
                     '60' => '1 Hour',
                     ], '30',array('class' => 'selectpicker form-control')) !!}
                </div>
                <div class="col-lg-6">
                    {!! Form::label('available_slot','Maximum Available Slot') !!}
                    {!! Form::number('available_slot', 1, array('class'=>'form-control','min'=>'1','autocomplete'=>'off')) !!}
                </div>
            </div>


        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
        </div>
        {{ Form::close() }}
    </div>
@endsection
