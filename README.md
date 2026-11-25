# Appointment package

Composer package: `heartland-computer-team/appointment-heartlandcomputer`

This package is designed for the Pratt Pest Control Laravel 10 application. It
provides public booking routes, admin appointment management, migrations,
views, mailers, calendar integration, and SMS event listeners.

## Install

Add the private GitHub repository to the consuming application's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/heartlandcomputer/appointment-heartlandcomputer"
        }
    ]
}
```

Then install the package:

```sh
composer require heartland-computer-team/appointment-heartlandcomputer:^1.0
```

GitHub authentication for the private repository must be configured in Composer.
Laravel discovers the package service providers automatically.

## Host application requirements

The package intentionally uses application-level services and models that are
specific to the Pratt Pest Control application, including:

- `App\Http\Controllers\Controller`
- `App\Helpers\Misc`
- `App\Repositories\Repository`, `SeoRepository`, `PageBannerRepository`, and
  `ZipCodeRepository`
- `App\Models\Agent`, `SmsLog`, and `ZipCode`

The host application must also configure the existing admin and permission
middleware, Twilio, Google Calendar, SEO, media library, and shared layout views.

## Routes and migrations

Public booking routes are registered under `/appointment`. Admin routes are
registered under `/admin/appointment`. Package migrations are loaded by the
Appointment service provider.
