@extends('layouts.app')

@section('title')
Felhasználói Profil - Eötvös Dö
@endsection


@php
    if($user->ejgClass == null) $msg="classRequired"
@endphp

@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">{{Auth::user()->is($user) ? "A Te adataid" : $user->name ." adatai"}}</h1>
    @if (isset($msg))
        @switch($msg)
            @case("classRequired")
                <div class="alert alert-danger">Osztály megadása szükséges!</div>
                @break
            @default

        @endswitch
    @endif

    <form action="{{route('user.update',$user->id)}}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="fullName">Név</label>
            <input type="text" class="form-control" name="fullName" id="fullName" disabled value="{{$user->name}}">
        </div>
        <div class="form-group">
            <label for="emailAddr">Email</label>
            <input type="text" class="form-control" name="emailAddr" id="emailAddr"  disabled value="{{$user->email}}"/>
        </div>
        <div class="form-group">
            <label for="ejgClass">Osztály</label>
            @if ($user->ejgClass && !Auth::user()->isAdmin())
                <select name="ejgClass" id="ejgClass" class="form-control" readonly disabled>
                    <option value="{{$user->ejgClass->name}}">{{$user->ejgClass->name}}</option>
                </select>
            @else
                <select name="ejgClass" id="ejgClass" class="form-control">
                    @foreach ($classes as $ejgclass )
                        <option value="{{$ejgclass->id}}">{{$ejgclass->name}}</option>
                    @endforeach
                </select>
            @endif
            </select>
        </div>
        @if (Auth::user()->isAdmin())
            <div class="form-group">
                <input type="text" class="form-control" name="permissions" value="{{$user->permissions()->where('event_id',null)->pluck('permission')}}">
            </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Adatok frissítése</button>
        </div>

    </form>
    <form action="{{route('user.destroy',$user->id)}}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <div class="form-group">
            <button type="submit" class="btn btn-danger">Profil törlése</button>
        </div>
    </form>
</div>

@endsection
