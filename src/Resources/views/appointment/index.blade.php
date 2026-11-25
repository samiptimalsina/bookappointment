@extends('layouts.app')
@section('scripts')
    <script type="application/javascript">
        $(document).ready(function () {
            var appointmentId = $('#appointmentType').change(function () {
                $('#timeSlotDiv').hide();
                var appointmentId = $(this).val();
                $("#my-calendar").empty();
                $.ajax({
                    url: "{{ route('appointment.get-available-days') }}",
                    type: "post",
                    data: {
                        'appointmentId': appointmentId,
                    },
                    success: function (response) {
                        $("#my-calendar").zabuto_calendar({
                            data: response.data,
                            show_previous: 0,
                            show_next: 7,
                            legend: [
                                {type: "text", label: "Available", badge: "&nbsp;&nbsp;&nbsp;"},
                            ],
                            action: function () {
                                var selectedDate = this.id;
                                selectedDate = selectedDate.replace('my-calendar_', '');
                                var newSelectedDate = moment(selectedDate, "YYYY/MM/DD").format("MM/DD/YYYY")
                                $('#selectedDateInfo').text(newSelectedDate);
                                $.ajax({
                                    url: "{{ route('appointment.get-available-slot') }}",
                                    type: "post",
                                    data: {
                                        'selectedDate': selectedDate,
                                        'appointmentTypeId': appointmentId,
                                    },
                                    success: function (response) {
                                        $('#timeSlotDiv').show();
                                        $('#dynamicTimeSlot').html(response.data);
                                        if (!response.isAppointmentTypeAvailable) {
                                            Swal.fire({title: 'Error!', text: 'Please choose appointment type first.'})
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus, errorThrown);
                                    }
                                });
                            },
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }).trigger('change');
        });
    </script>
@endsection

@section('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebPage",
          "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
              {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": {
                  "@id": "{{ route('home') }}"
                }
              },
              {
                "@type": "ListItem",
                "position": 2,
                "name": "Appointment"
              }
            ]
          }
        }

    </script>
@endsection

@section('content')

    <section class="page-title" style="@if($pageBanner)
            background-image: url({{ $pageBanner->getMedia('pageBanner')->first()->getUrl('large') }});
    @else
            background-image: url({{ asset('images/bg-3.jpg') }});
    @endif ">
        <div class="auto-container">
            <h1>Appointment</h1>
            <ul class="breadcrumb text-center" vocab="https://schema.org/" typeof="BreadcrumbList">
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                    <a property="item" typeof="WebPage" href="{{ route('home') }}">
                        <span property="name">Home</span></a>
                    <meta property="position" content="1">
                </li>
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                    <span property="name">Appointment</span>
                    <meta property="position" content="2">
                </li>
            </ul>
        </div>
    </section>

    <section class="service-details" style="padding: 60px 0 100px;">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="context-block">
                        <h3>Book Appointment</h3>
                        <section class="daate">
                            <div class="container" style="padding-right: 0px;  padding-left: 0px;">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group" style="width: 400px;">
                                            <label for="foo" style="font-size: 18px;"><strong>Choose Appointment
                                                    Type:</strong></label>
                                            <select class="form-control selectpicker appointmentType"
                                                    id="appointmentType">
                                                <option value="">Select Appointment Type</option>
                                                @foreach($appointmentTypes as $appointmentType)
                                                    <option
                                                        value="{{ $appointmentType->id }}">{{ $appointmentType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="calender-section">
                            <div class="container" style="  padding-right: 0px;  padding-left: 0px; padding-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="foo" style="font-size: 18px;"><strong>Select Your Appointment
                                                Date</strong></label>
                                        <div id="my-calendar"></div>
                                    </div>
                                </div>

                            </div>
                        </section>
                        <section class="appointment-slot-area">
                            <div class="container" style="padding-right: 0px;  padding-left: 0px; display: none;"
                                 id="timeSlotDiv">
                                <div class="row">
                                    <div class="col-12">
                                        <label style="font-size: 18px;"><strong>Selected Date : <span
                                                    id="selectedDateInfo"></span></strong></label>
                                        <br>
                                        <label style="font-size: 18px;"><strong>Select Your Time:</strong></label>
                                        <div class="button-grid-group">
                                            <div class="row" id="dynamicTimeSlot"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
