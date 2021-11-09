@extends('layouts.app')

@section('title')
    E5N Új Esemény
@endsection

@section('content')
<div class="card container">
    <form action="{{route('event.store')}}" method="POST">
        @csrf

        <div class="form-group">
            <label for="code">Esemény kódja</label>
            <input name="code" id="code" type="text" class="form-control" maxlength="13" >
        </div>

        <div class="form-group">
            <label for="title">Esemény címe</label>
            <input name="title" id="title" type="text" class="form-control" >
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Új esemény létrehozása</button>
        </div>
    </form>
</div>
@endsection
