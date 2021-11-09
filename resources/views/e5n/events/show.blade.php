@extends('layouts.app')

@section('title')
    Program Információk
@endsection

@section('content')
    @can("signup",$event->resource)
        <a href="/e5n/scanner" class="btn btn-primary">Részvétel Regisztrálása</a>
    @endcan
    <show-event :user='{{$isUser}}' :userrating='{{$userRating}}' :event='@json($event)' >
@endsection
