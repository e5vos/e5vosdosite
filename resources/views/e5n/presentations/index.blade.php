@extends('layouts.app')

@section('title')
    Előadás Jelentkezés
@endsection

@section('content')
    <div class="container" style="background-color:rgba(255, 255, 255, 0.5)">
        <div class="container py-2" style="width:fit-content;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
            <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>

        </div>
        <presentations></presentations>
        <br/>
    </div>
@endsection
