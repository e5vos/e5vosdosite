@extends('layouts.app')

@section('title')
QR kód olvasó
@endsection

@section('content')
    <qr-reader :currentevent='@json($event)'>
@endsection
