@extends('layouts.app')

@section('title')
    Program Információk
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="jumbotron text-center h-100">
                    <h1>{{$event->name}}</h1> <br/>
                    <img src="{{asset('images/default.png')}}" class="img-fluid rounded" alt="A képet nem sikerült betölteni."/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="jumbotron text-center">{{$event->description}}</div>
                <div class="jumbotron text-center">
                    @if ($event->location != null)
                        <a href="/e5n/locations/{{$event->location_id}}"><h4>Helyszín: {{$event->location->name}}</h4></a>
                    @endif
                    <h4 class="align-middle">Szervező osztály(ok):
                    @foreach ($event->orga_classes as $ejg_class)
                        {{$ejg_class->name}}
                    @endforeach</h4>
                    @if($event->is_presentation)
                        <a href="/e5n/presentations"><button class="btn btn-success">Előadás Jelentkezés</button></a>
                    @elseif ($event->capacity!=null)
                        <button onclick="signUpFunc" class="btn btn-success">Jelentkezés!</button>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="jumbotron text-center h-90 align-middle">
                    <h2>Értékelés:</h2><br/>
                    <h1>{{round($event->ratingValue,1)}}/10</h1> <br/>
                    <form action="idk" method="get">
                        @csrf
                        <input type="range" class="form-range form-fluid" min="1" max="10" id="ratingValue">
                        <button class="btn btn-primary" type="submit">Értékel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
