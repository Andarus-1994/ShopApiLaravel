@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => 'Shopius.net'])
<!-- header here -->
@endcomponent
@endslot
<h2 style="text-align: center;">Successfully registered with username: <b>{{$user}}</b>,</h2>
<p style="text-align: center">Click here to verify your email: </p>
@component('mail::button', ['url' => $frontend_url.'signup/'.$token])Verify
@endcomponent
<h1 style="text-align: center">Happy shopping! </h1>
{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<!-- footer here -->
{{ config('app.name') }} Team
@endcomponent
@endslot
@endcomponent
