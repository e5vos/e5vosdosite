@extends('layouts.app')

@section('title')
Fasszopók - E5N Csapatok
@endsection


@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">{{$team->name}} - E5N Csapatok</h1>
    <div class="table-responsive-md">
        <form action="{{route('team.member.leave',$team->code)}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger"> Kilépés a csapatból </button>
        </form>
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th scope="col">Név</th>
                    <th scope="col">Státusz</th>
                    <th scope="col">E-mail cím</th>
                    <th scope="col">Osztály</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($team->memberships as $membership)
                <tr>
                    <td>{{$membership->user->name}}</td>
                    <td>{{__('e5n.team_role.'.$membership->role)}}</td>
                    <td>{{$membership->user->email}}</td>
                    <td>{{$membership->ejgclass ? $membership->ejgclass->name: "Nem ismert"}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
