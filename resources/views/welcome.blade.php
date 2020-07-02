@extends('layouts.app')

@section('content')

<div class="container-fluid" style="color:white">
    <div class="window-content">
        <div class="jumbotron" style="background-color:rgba(51, 3, 185, 0.89)">
            <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Üdv az új DÖ Honlapon!</h1>
            <p class="lead"></p>
            <hr class="my-4">
            <p>Egy teljes átírást követően alfa verzióban</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="#" role="button">Tudj meg többet!</a>
            </p>
        </div>

        <div class="row">
            <div class="col-md-6 container">
                <div class="jumbotron" style="background-color:rgba(100, 212, 133, 0.89)">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Eötvös Napok</h1>
                    <p class="lead"></p>
                    <hr class="my-4">
                    <p>Egy teljes átírást követően alfa verzióban</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="#" role="button">Tudj meg többet!</a>
                        <a class="btn btn-warning btn-lg" href="#" role="button">Jelentkezz szervezőnek!</a>
                        <a class="btn btn-danger btn-lg" href="#" role="button">Írd meg észrevételeid!</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 container" >
                <div class="jumbotron" style="background-color:rgba(82, 82, 100, 0.89)">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Hamarosan</h1>
                    <p class="lead"></p>
                    <hr class="my-4">
                    <p>A DÖ Honlap egy teljes átírást követően alfa verzióban</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="#" role="button">Tudj meg többet!</a>
                        <a class="btn btn-warning btn-lg" href="#" role="button">Jelentkezz fejlesztőnek!</a>
                        <a class="btn btn-danger btn-lg" href="#" role="button">Írd meg észrevételeid!</a>
                    </p>
                </div>
            </div>
        </div class="row">

    </div>
</div>
@endsection


