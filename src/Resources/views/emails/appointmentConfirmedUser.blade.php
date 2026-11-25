@extends('layouts.email.app')
@section('title', 'Contact Message')

@section('previewText')
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
        <tbody>
        <tr>
            <td class="o_hide" align="center"
                style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">
                Dear User<br>
                Appointment has been Confirmed
            </td>
        </tr>
        </tbody>
    </table>
@endsection

@section('content')
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
        <tbody>
        <tr>
            <td class="o_bg-light o_px-xs" align="center"
                style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                <!--[if mso]>
                <table width="800" cellspacing="0" cellpadding="0" border="0" role="presentation">
                    <tbody>
                    <tr>
                        <td><![endif]-->
                <table class="o_block-lg" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation"
                       style="max-width: 800px;margin: 0 auto;">
                    <tbody>
                    <tr>
                        <td class="o_bg-ultra_light o_px-md o_py-xl o_xs-py-md" align="center"
                            style="background-color: #ebf5fa;padding-left: 24px;padding-right: 24px;padding-top: 20px;padding-bottom: 20px;">
                            <!--[if mso]>
                            <table width="584" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tbody>
                                <tr>
                                    <td align="center"><![endif]-->
                            <div class="o_col-6s o_center o_sans o_text-md o_text-light"
                                 style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;max-width: 584px;color: #82899a;text-align: center;">
                                <h2 class="o_heading o_text-dark o_mb-xxs"
                                    style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 23px;line-height: 39px;">
                                    Appointment has been Confirmed</h2>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
        <tbody>
        <tr>
            <td class="o_bg-light o_px-xs" align="center"
                style="background-color: #dbe5ea;padding-left: 8px;padding-right: 8px;">
                <!--[if mso]>
                <table width="800" cellspacing="0" cellpadding="0" border="0" role="presentation">
                    <tbody>
                    <tr>
                        <td><![endif]-->
                <table class="o_block-lg" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation"
                       style="max-width: 800px;margin: 0 auto;">
                    <tbody>
                    <tr>
                        <td class="o_bg-white o_px-md o_py" align="center"
                            style="background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;">
                            <!--[if mso]>
                            <table width="584" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tbody>
                                <tr>
                                    <td align="center"><![endif]-->
                            <div class="o_col-6s o_sans o_text o_text-secondary "
                                 style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;max-width: 584px;color: #424651;text-align: left;">
                                <p style="margin-top: 0px;margin-bottom: 0px;">


                                <table>
                                    <tr>
                                        <td>
                                            <p>
                                                Dear <strong>{{ $appointment->first_name.' '.$appointment->last_name }}
                                                    ,</strong><br><br>
                                            <p>Your Appointment has been Confirmed with following Detail</p>
                                            <div>
                                                <table border="1" cellspacing="10" cellpadding="10"
                                                       style="width:100%;max-width:100%;margin-bottom:1rem;background-color:transparent">
                                                    <tr>
                                                        <td>First Name</td>
                                                        <td>{{ $appointment->first_name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Last Name</td>
                                                        <td>{{ $appointment->last_name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Phone No</td>
                                                        <td>{{ $appointment->phone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td>
                                                        <td>{{ $appointment->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td>
                                                        <td>{{ $appointment->address }}
                                                            , {{ $appointment->zipCode->city->name }}
                                                            , {{ $appointment->zipCode->state->name }}
                                                            , {{ $appointment->zipCode->code }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date</td>
                                                        <td>{{ \App\Helpers\Misc::usFormatDate($appointment->appointment_date) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Time</td>
                                                        <td>{{ date('g:i a', strtotime($appointment->start_time)) . ' - ' . date('g:i a', strtotime($appointment->end_time)) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Appointment Type</td>
                                                        <td>{{ $appointment->appointmentSetup->appointmentType->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Message</td>
                                                        <td>
                                                            {{ $appointment->message }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            If you want to cancel this appointment please click <a
                                                                href="{{ route('appointment.cancel', Crypt::encrypt($appointment->id)) }}">here</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>


                                </p>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
        </tbody>
    </table>
@endsection
