@extends('layouts.app')

@section('title')
    Program Információk
@endsection

@section('content')
    @if($event->permissions()->pluck('user_id')->contains(Auth::user()->id) || Auth::user()->isAdmin())
        <a href="/e5n/scanner" class="btn btn-primary">Részvétel Regisztrálása</a>
    @endif
    <show-event :user='{{$isUser}}' :user-rating='{{$userRating}}' :event='@json($event)' >
@endsection
