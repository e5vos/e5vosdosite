@extends('layouts.app')

@section('title')
    Program Információk
@endsection

@section('content')
    <show-event :user='{{$isUser}}' :user-rating='{{$userRating}}' :event='@json($event)' >
@endsection
