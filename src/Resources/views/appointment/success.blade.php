@extends('layouts.app')
@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/add-to-calendar-button@1/assets/css/atcb.min.css">
    <script src="https://cdn.jsdelivr.net/npm/add-to-calendar-button@1" async defer></script>
@endsection
@section('content')

    @php
        $eventName = 'Appointment With Heartland Computer';
        if(isset($appointment->appointmentMaster->type->name))
            {
                    $eventName = $appointment->appointmentMaster->type->name .' Appointment With Pratt Pest Control';
            }
    @endphp

    <section class="page-title" style="@if($pageBanner)
    background-image: url({{ $pageBanner->getMedia('pageBanner')->first()->getUrl('large') }});
    @else
    background-image: url({{ asset('images/bg-3.jpg') }});
    @endif ">
        <div class="auto-container">
            <h1>Appointment Success</h1>
            <ul class="breadcrumb text-center" vocab="https://schema.org/" typeof="BreadcrumbList">
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                    <a property="item" typeof="WebPage" href="{{ route('home') }}">
                        <span property="name">Home</span></a>
                    <meta property="position" content="1">
                </li>
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                    <span property="name">Appointment Success</span>
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
                        <h5 class="pb-4">Dear <strong>{{ $appointment->first_name }}</strong>,</h5>
                        <div class="text">
                            <strong>
                                Your {{ $eventName }} has been booked success
                                for {{ \App\Helpers\Misc::usFormatDate($appointment->appointment_date)  }}
                                ({{ date("h:i a", strtotime($appointment->start_time))   }}
                                - {{ date("h:i a", strtotime($appointment->end_time))  }})
                            </strong>
                        </div>
                        <div class="text">
                            <div class="atcb" style="display:none;">
                                {
                                "name":"Appointment With Pratt Pest Control",
                                "description":"{{ $eventName }}
                                at {{ \App\Helpers\Misc::usFormatDate($appointment->appointment_date)  }}
                                ({{ date("h:i a", strtotime($appointment->start_time))   }}
                                - {{ date("h:i a", strtotime($appointment->end_time))  }})",
                                "startDate":"{{ $appointment->appointment_date }}",
                                "endDate":"{{ $appointment->appointment_date }}",
                                "startTime":"{{ $appointment->start_time }}",
                                "endTime":"{{ $appointment->end_time }}",
                                "location":"{{ $appointment->address . ''.  $appointment->zip }}
                                "
                                ,
                                "label":"Add to Calendar",
                                "options":[
                                "Apple",
                                "Google",
                                "iCal",
                                "Microsoft365",
                                "MicrosoftTeams",
                                "Outlook.com",
                                "Yahoo"
                                ],
                                "timeZone":"{{ config('app.timezone') }}",
                                "trigger":"click",
                                "inline":true,
                                "listStyle":"modal",
                                "iCalFileName":"Reminder-Event"
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
