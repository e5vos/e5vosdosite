@extends('layouts.app')


@section('title')
    E5N Előadások - Adminisztrátori felület
@endsection


@section('content')
<div class="container">
    @foreach ($presentations->orderBy('slot')->get()->groupBy('slot') as $i=>$presentations)
        <div class="table-responsive-md">
        <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;">
            <thead class="thead-dark">
                <tr><th scope="col" colspan="5">{{$i}}. előadássáv</th></tr>
                <tr>
                    <th scope="col" rowspan="2">Előadó</th>
                    <th scope="col" rowspan="2">Előadás címe</th>
                    <th scope="col">Kapacitás</th>
                    <th scope="col">Foglalt helyek</th>
                    <th scope="col" rowspan="2">Megjelent</th>
                </tr>
                <tr>
                    <th scope="row" colspan="2"><button class="btn btn-warning" v-on:click="fillUpAll()">Automatikus feltöltés</button></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($presentations as $presentation)
                    <tr>
                        <td>{{$presentation->organiser_name}}</td>
                        <td>{{$presentation->name}}</td>
                        <td>{{$presentation->capacity ? $presentation->capacity : "Korlátlan"}}</td>
                        <td><button class="btn btn-warning" {{$presentation->capacity - $presentation->occupancy <= 0 ? "disabled" : ""}}>{{$presentation->occupancy}}</button></td>
                        <td><a class="btn btn-warning" href="{{route('presentation.show',$presentation->code)}}">{{$presentation->signups()->where('present',1)->count()}}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
        </div>
    @endforeach
</div>
@endsection
