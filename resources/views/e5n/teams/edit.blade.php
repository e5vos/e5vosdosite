@extends('layouts.app')

@section('title')
Fasszopók - E5N Csapatok
@endsection


@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">{{$team->name}} - E5N Csapatok</h1>
    <div class="table-responsive-md">
        <form action="{{route('team.member.manage',$team->code)}}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @csrf

            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">Név</th>
                        <th scope="col">Státusz</th>
                        <th scope="col">E-mail cím</th>
                        <th scope="col">Osztály</th>
                        <th scope="col">Kezelés</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($team->memberships as $memberShip)
                        <tr>
                            <td>{{$memberShip->user->name}}</td>
                            <td>{{$memberShip->user->email}}</td>
                            <td>{{__('e5n.team_role.'.$memberShip->role)}}</td>
                            <td>{{$memberShip->user->ejgClass ? $memberShip->user->ejgClass->name : "Nem elérhető"}}</td>
                            <td>
                                @if ($memberShip->role=="member")
                                    <button type="submit" name="promote" value="{{$memberShip->user->id}}" class="btn btn-success">Menedzserré tétel</button>
                                @endif
                                <button class="btn btn-danger" type="submit" name="kick" value="{{$memberShip->user->id}}" {{Auth::user()->is($memberShip->user) ? "disabled" : "" }}>Kirúgás</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tbody>
                    <tr>
                        <th scope="row">E-mail cím:</th>
                        <td colspan="2"><input type="email" name="email" id="email" placeholder="pelda.bela@e5vos.hu"></td>
                        <td><button class="btn btn-info" type="submit" name="new" value="add">Tag hozzáadása</button></td>
                        <td><button class="btn btn-danger" type="submit" name="leave" value="yes"> Kilépés a csapatból</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
        </table>
    </div>

</div>

@endsection
