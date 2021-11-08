@extends('layouts.app')

@section('title')
    Program Információk
@endsection

@section('content')
    <show-event :eventcode="'{{$code}}'">
@endsection
