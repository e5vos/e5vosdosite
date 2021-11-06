@extends('layouts.app')

@section('title')
Felhasználói Profil - Eötvös Dö
@endsection


@php
    $msg="classRequired"
@endphp

@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">A Te adataid</h1>
    @if (isset($msg))
        @switch($msg)
            @case("classRequired")
                <div class="alert alert-danger">Osztály megadása szükséges!</div>
                @break
            @default

        @endswitch
    @endif

    <form action="/user" method="PUT">
        @csrf
        <div class="form-group">
            <label for="fullName">Név</label>
            <input type="text" class="form-control" name="fullName" id="fullName" disabled value="Kovács Béla">
        </div>
        <div class="form-group">
            <label for="emailAddr">Email</label>
            <input type="text" class="form-control" name="emailAddr" id="emailAddr"  disabled value="kovacs.bela@e5vos.hu"/>
        </div>
        <div class="form-group">
            <label for="ejgClass">Osztály</label>
            @if (Auth::user()->ejgClass)
                <select name="ejgClass" id="ejgClass" class="form-control" readonly disabled>
                    <option value="{{Auth::user()->ejgClass->name}}">{{Auth::user()->ejgClass->name}}</option>
                </select>
            @else
                <select name="ejgClass" id="ejgClass" class="form-control">
                    @foreach (\App\EJGClass::all() as $ejgclass )
                        <option value="{{$ejgclass->id}}">{{$ejgclass->name}}</option>
                    @endforeach
                </select>
            @endif

            </select>


        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Adatok frissítése</button>
        </div>
    </form>
    <form action="/user" method="DELETE">
        @csrf
        <div class="form-group">
            <button type="submit" class="btn btn-danger">Profil törlése</button>
        </div>
    </form>
</div>

@endsection
