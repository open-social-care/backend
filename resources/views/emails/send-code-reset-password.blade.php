@extends('emails.layout')

@section('content')
    <p style="font-size: 20px;">@lang('messages.email.hello')</p>
    <p>@lang('messages.email.reset_password.message')</p>
    <p>@lang('messages.email.reset_password.token_message') <strong> {{ $token }} </strong> </p>
    <p>@lang('messages.email.reset_password.observation_message')</p>
    <br>
    <p style="margin-top: 20px;">@lang('messages.email.att')</p>
    <p style="line-height: 0 !important;">@lang('messages.email.team_name')</p>
@endsection