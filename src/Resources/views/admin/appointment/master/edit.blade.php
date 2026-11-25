@extends('layouts.admin.app')
@section('scripts')
    {{--    <script>--}}
    {{--        document.addEventListener('DOMContentLoaded', function (e) {--}}
    {{--            FormValidation.formValidation(--}}
    {{--                document.getElementById('wsValidator'),--}}
    {{--                {--}}
    {{--                    fields: {--}}
    {{--                        "question": {--}}
    {{--                            validators: {--}}
    {{--                                notEmpty: {--}}
    {{--                                    message: 'Question is required'--}}
    {{--                                }--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                        "answer": {--}}
    {{--                            validators: {--}}
    {{--                                notEmpty: {--}}
    {{--                                    message: 'Answer is required'--}}
    {{--                                }--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                    },--}}
    {{--                    plugins: {--}}
    {{--                        trigger: new FormValidation.plugins.Trigger(),--}}
    {{--                        bootstrap: new FormValidation.plugins.Bootstrap(),--}}
    {{--                        submitButton: new FormValidation.plugins.SubmitButton(),--}}
    {{--                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            );--}}
    {{--        });--}}
    {{--    </script>--}}
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Appointment Schedule
                    <span class="d-block text-muted pt-2 font-size-sm">Edit Appointment Schedule</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.appointment.master-setup.specific-day') }}"
                   class="btn btn-primary font-weight-bolder">
                    <i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>


        {!! Form::open(array('route' => ['admin.appointment.master-setup.update',$masterSetup->id],'class'=>'m-form m-form--fit ','id'=>'validator', 'files' => 'true')) !!}

        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-6">
                    {!! Form::label('Appointment Type','Appointment Type') !!}
                    {!! Form::text('', $masterSetup->type->name, array('class'=>'form-control','readonly'=>true)) !!}
                </div>
                <div class="col-lg-6">
                    {!! Form::label('Appointment Date','Appointment Date') !!}
                    {!! Form::text('', \App\Helpers\Misc::usFormatDate($masterSetup->appointment_date), array('class'=>'form-control','readonly'=>true)) !!}
                </div>
            </div>
            <div class="form-group  row ">
                <div class="col-6">
                    {!! Form::label('Start Time','Start Time') !!}
                    {!! Form::text('', date("h:i a", strtotime($masterSetup->start_time)), array('class'=>'form-control','readonly'=>true)) !!}
                </div>
                <div class="col-6">
                    {!! Form::label('End Time','End Time') !!}
                    {!! Form::text('',  date("h:i a", strtotime($masterSetup->end_time)), array('class'=>'form-control','readonly'=>true)) !!}
                </div>
            </div>

            <div class="form-group  row ">
                <div class="col-6">
                    {!! Form::label('available_slot','Maximum Available Slot') !!}
                    {!! Form::number('available_slot', $masterSetup->available_slot, array('class'=>'form-control','min'=>'1','autocomplete'=>'off')) !!}
                </div>
                <div class="col-6"></div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::hidden('id', $masterSetup->id)!!}
        {{ Form::close() }}
    </div>
@endsection
