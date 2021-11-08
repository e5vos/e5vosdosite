@extends('layouts.app')

@section('content')

<div class="container">
    <div class="window-content">
        <div class="jumbotron card" >
            <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Üdv az új DÖ Honlapon!</h1>
            <hr class="my-4">
            <p>Egy teljes átírást követően alfa verzióban</p>
        </div>

        <div class="row">
            <div class="col-md-6 container">
                <div class="jumbotron card">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;">Előadás Jelentkezés</h1>
                    <p class="lead"><a class="btn btn-primary btn-lg" href="/e5n/presentations" role="button">Tovább a Jelentkezéshez!</a></p>
                </div>
            </div>
            <div class="col-md-6 container" >
                <div class="jumbotron">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Hamarosan</h1>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


