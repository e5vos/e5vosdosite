@extends('layouts.app')

@section('title')
Csapatok - E5N
@endsection


@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">A Te csapataid</h1>
    <div class="table-responsive-sm">
        <form action="{{route('team.delete')}}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">Csapatnév</th>
                        <th scope="col">Csapatkód</th>
                        <th scope="col">Tagok</th>
                        <th scope="col">Kezelés</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (Auth::user()->teams as $team)
                        <tr>
                            <td>{{$team->name}}</td>
                            <td><a href="{{route('qr.code',$team->code)}}" class="form-control">{{$team->code}}</a></td>
                            <td>{{$team->size()}}</td>
                            <td>
                                @if ($team->admins()->get()->contains(Auth::user()))
                                    <a href="{{route('team.edit',$team->code)}}" class="btn btn-info">Kezelés</a>
                                @else
                                    <a href="{{route('team.show',$team->code)}}" class="btn btn-info">Megtekint</a>
                                @endif
                                <button type="submit" name="team" value="{{$team->code}}" class="btn btn-danger">{{"Kilépés"}}</button>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        <h3>Új csapat</h3>
        <form action="{{route('team.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Csapat neve</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <button class="btn btn-primary" type="submit">Új csapat</button>
        </form>
    </div>

</div>

@endsection
