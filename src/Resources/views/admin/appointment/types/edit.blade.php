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
                <h3 class="card-label">Appointment Type
                    <span class="d-block text-muted pt-2 font-size-sm">Edit Appointment Type</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.appointment.types.index') }}" class="btn btn-primary font-weight-bolder">
                    <i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
        {{ Form::open(array('route' => ['admin.appointment.types.update',$type->id],'files'=>true,'method' => 'PUT','id'=>'wsValidator')) }}
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-12">
                    {!! Form::label('name','Name *' ) !!}
                    {!! Form::text('name', $type->name , array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-group  row ">
                <div class="col-12">
                    {!! Form::label('description','Description ' ) !!}
                    {!! Form::textarea('description', $type->description , array('class'=>'form-control ckEditor')) !!}
                </div>
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('icon','Icon') !!}
                <input type="file" name="icon" class="form-control">
                @if($type->getMedia('appointmentType')->first())
                    <br>
                    <p>Old Icon</p>
                    <a href="{{ $type->getMedia('appointmentType')->first()->getUrl()}}" target="_blank"><img
                            src="{{ $type->getMedia('appointmentType')->first()->getUrl('small')}}" ></a>
                @endif
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
        </div>
        {!! Form::hidden('id', $type->id)!!}
        {{ Form::close() }}
    </div>
@endsection
