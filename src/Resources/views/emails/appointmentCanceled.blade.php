@extends('layouts.email.app')
@section('title', 'Appointment has been Confirmed')

@section('previewText')
    <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
        <tbody>
        <tr>
            <td class="o_hide" align="center"
                style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;visibility: hidden;">
                Dear User<br>
                Appointment has been Cancelled
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
                                    Appointment has been Cancelled</h2>
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
                                                    ,</strong><br><br></p>
                                            <p>Your appointment at {{ \App\Helpers\Misc::usFormatDate($appointment->appointment_date) }} {{ date('g:i a', strtotime($appointment->start_time)) . ' - ' . date('g:i a', strtotime($appointment->end_time)) }} has been canceled. </p>
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
