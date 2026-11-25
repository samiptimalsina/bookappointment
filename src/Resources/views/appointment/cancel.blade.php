@extends('layouts.app')
@section('scripts')
@endsection
@section('content')

    <section class="page-title" style="@if($pageBanner)
    background-image: url({{ $pageBanner->getMedia('pageBanner')->first()->getUrl('large') }});
    @else
    background-image: url({{ asset('images/bg-3.jpg') }});
    @endif ">
        <div class="auto-container">
            <h1>Appointment Cancelled</h1>
            <ul class="breadcrumb text-center" vocab="https://schema.org/" typeof="BreadcrumbList">
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                    <a property="item" typeof="WebPage" href="{{ route('home') }}">
                        <span property="name">Home</span></a>
                    <meta property="position" content="1">
                </li>
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item active">
                    <span property="name">Appointment Cancelled</span>
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
                        <h5 class="pb-4">Appointment Cancelled</strong>,</h5>
                        <div class="text">
                            <p>If you are planing to schedule on another date please click <a href="{{ route('appointment.index') }}">here</a>.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
