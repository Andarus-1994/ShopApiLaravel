@component('mail::message')
    <h1>We have received your request to reset your account password</h1>
    <p>You can use the following code to recover your account:</p>
    <h2>Your Username is: <b>{{$user}}</b></h2>
    @component('mail::panel')
        {{ $code }}
    @endcomponent <p>The allowed duration of the code is one hour from the time the message was sent</p>
@endcomponent
