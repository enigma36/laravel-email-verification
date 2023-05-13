<x-mail::message>
 Hello {{$user->name}}
<x-mail::button :url="route('verify_email',$user->email_verification_code)">
Verify Email
</x-mail::button>
    <p>or paste the link below in your browser</p>
    <p><a href="{{route('verify_email',$user->email_verification_code)}}"> {{route('verify_email',$user->email_verification_code)}}</a></p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

