@extends('layouts.app')


@section('title')
{{$presentationResource->name}} - Jelenléti Ív
@endsection


@section('content')
<div class="container-fluid">
    <div class="py-2 container-fluid" style="background-color:rgba(255, 255, 255, 0.5)">

        <attendance-checker :presentation='@json($presentationResource)' />
    </div>
</div>
@endsection
