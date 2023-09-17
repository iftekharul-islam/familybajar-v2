@component('mail::message')

    <body class="bg-light">
        <div class="container">
            <div class="card p-6 p-lg-10 space-y-4">
                <h1 class="h3 fw-700">
                    Hello {{ $user->name }}
                </h1>
                <p>
                    We received a request to reset your password. Use the link below to set up a new password for your
                    account. <br />
                    If you did not request to reset your password, ignore this email and do not share this link
                    with any other person.
                </p>
                <a class="btn btn-primary p-3 fw-700" href="{{ $url }}">New Password</a>
            </div>
            <div class="text-muted text-center my-6">
                Thanks, <br />{{ config('app.name') }}
            </div>
        </div>
    </body>
@endcomponent
