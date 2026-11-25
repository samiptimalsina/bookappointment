@extends('layouts.app')
@section('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function (e) {
        //     FormValidation.formValidation(
        //         document.getElementById('validator'),
        //         {
        //             fields: {
        //                 first_name: {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'The first name is required'
        //                         },
        //                         stringLength: {
        //                             min: 2,
        //                             max: 30,
        //                             message: 'The name must be more than 2 and less than 30 characters long'
        //                         },
        //                     }
        //                 },
        //                 last_name: {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'The last name is required'
        //                         },
        //                         stringLength: {
        //                             min: 2,
        //                             max: 30,
        //                             message: 'The last name must be more than 2 and less than 30 characters long'
        //                         },
        //                     }
        //                 },
        //                 email: {
        //                     validators: {
        //                         emailAddress: {
        //                             message: 'The value is not a valid email address'
        //                         },
        //                         notEmpty: {
        //                             message: 'The email is required'
        //                         }
        //                     }
        //                 },
        //                 phone: {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'The phone is required'
        //                         },
        //                         regexp: {
        //                             regexp: /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/,
        //                             message: 'Please use valid us phone no. seperated by -.',
        //                         },
        //                     }
        //                 },
        //
        //                 address: {
        //                     validators: {
        //                         notEmpty: {
        //                             message: 'The address is required'
        //                         }
        //                     }
        //                 },
        //                 zip: {
        //                     validators: {
        //                         regexp: {
        //                             regexp: /^\d{5}$/,
        //                             message: 'The US zip code must contain 5 digits',
        //                         },
        //                         notEmpty: {
        //                             message: 'The zip code message is required'
        //                         }
        //                     },
        //                 },
        //             },
        //             plugins: {
        //                 defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        //                 trigger: new FormValidation.plugins.Trigger(),
        //                 bootstrap: new FormValidation.plugins.Bootstrap(),
        //                 submitButton: new FormValidation.plugins.SubmitButton(),
        //             },
        //         }
        //     );
        // });
        $(document).ready(function () {
            $('#zip_code_id').select2({
                placeholder: "Choose zip *",
                minimumInputLength: 3,
                theme: 'bootstrap-5',
                ajax: {
                    url: "{{ route('location.zip-search') }}",
                    dataType: 'json',
                    type: "POST",
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection
@section('content')

    <section class="page-title" style="@if($pageBanner)
    background-image: url({{ $pageBanner->getMedia('pageBanner')->first()->getUrl('large') }});
    @else
    background-image: url({{ asset('images/bg-3.jpg') }});
    @endif ">
        <div class="auto-container">
            <h1>Book Appointment</h1>
            <ul class="breadcrumb text-center" vocab="https://schema.org/" typeof="BreadcrumbList">
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                    <a property="item" typeof="WebPage" href="{{ route('home') }}">
                        <span property="name">Home</span></a>
                    <meta property="position" content="1">
                </li>
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                    <span property="name">Book Appointment</span>
                    <meta property="position" content="2">
                </li>
            </ul>
        </div>
    </section>

    <section class="contact-form-section" style="padding: 70px 0 20px;">
        <div class="auto-container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="context-block">
                        <h3>Book Appointment</h3>
                        <div class="text">
                            <div class="alert alert-success mt-3" role="alert">
                                Booking Appointment For Date
                                : <strong>{{ \App\Helpers\Misc::usFormatDate($appointmentMaster->appointment_date)  }}
                                    ({{ date("h:i a", strtotime($appointmentMaster->start_time))   }}
                                    - {{ date("h:i a", strtotime($appointmentMaster->end_time))  }})</strong><br>
                                Appointment Type : <strong>{{ $appointmentMaster->type->name }}</strong>
                            </div>
                        </div>

                        <div class="contact-form">

                            @include('layouts.inc.alert')

                            {!! Form::open(array('route' => ['appointment.book.post',Crypt::encrypt($appointmentMaster->id)], 'class' => 'contact-form','id'=>'validator')) !!}
                            <div class="row">

                                <div class="form-group col-md-6">
                                    {!! Form::text('first_name', null, array('class'=>'form-control','placeholder'=>'Your First Name *', 'required'=>true)) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::text('last_name', null, array('class'=>'form-control','placeholder'=>'Your Last Name*', 'required'=>true)) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::email('email', null, array('class'=>'form-control','placeholder'=>'Your Email *', 'required'=>true)) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::text('phone', null, array('class'=>'form-control','placeholder'=>'Your Phone No. *','id'=>'phone_appointment', 'required'=>true)) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::text('address', null, array('class'=>'form-control','placeholder'=>'Your Address *', 'required'=>true)) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {!! Form::select('zip', [],  null, array('class' => 'form-control zip_code_id', 'multiple'=>false,'id'=>'zip_code_id','placeholder'=>'Zipcode')) !!}
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::textarea('message', null, array('class'=>'form_message','rows'=>1,'placeholder'=>'Your message')) !!}
                                </div>

                                <div class="form-group col-md-12">
                                    {!! RecaptchaV3::field('book_appointment') !!}
                                </div>

                                <div class="form-group col-md-12">
                                    <div class="text-center">
                                        <button class="theme-btn btn-style-one" type="submit">
                                            <span>Book Appointment </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
