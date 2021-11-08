@extends('layouts.app')

@section('title')
Felhasználói Profilok - Eötvös Dö
@endsection

@section('content')

<div class="container card">
    <h1 class="text-center" style="font-size:32px;">Felhasználók</h1>

    <table class="table">
        <thead>
            <thead>
                <th scope="col">Név</th>
                <th scope="col">Osztály</th>
                <th scope="col">Email</th>
                <th scope="col">Szerkesztés</th>
            </thead>
        </thead>
        <tbody>
            @foreach(\App\Models\User::all() as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->ejgClass ? $user->ejgClass->name : "Ismeretlen"}}</td>
                    <td>{{$user->email}}</td>
                    <td><a class="btn btn-warning" href="{{route('user.edit',$user->id)}}">Szerkesztés</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
