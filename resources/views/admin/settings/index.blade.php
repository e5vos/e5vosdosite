@extends('layouts.app')

@section('title')
    Az oldal beállításai
@endsection

@section('content')
    <site-settings :settings='@json($settings)'>
@endsection
