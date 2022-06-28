@if(isset($content['is_admin']) && ($content['is_admin'] == true))

    @component('mail::message')
    Hello {{ config('app.name') }},

    New query has been recieved from <strong>{{ $content['first_name'] }}</strong> with following details:

    {!! $content['body'] !!}

    Thanks,
    {{ $content['first_name'] }}
    @endcomponent

@else

    @component('mail::message')
    Hi {{@$content['first_name']}} ,

    Your Query has been submitted with following details.
    Name:{{ $content['first_name']}} {{ $content['last_name'] }}
    Email:{{$content['email'] }}
    Message:{{ $content['message'] }}

    Thanks,
    {{ config('app.name') }}
    @endcomponent

@endif
