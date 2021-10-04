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
                <a class="btn btn-primary btn-lg" href="https://www.facebook.com/messages/t/100022524080676" role="button">Tudj meg többet!</a>
            </p>
                <a class="btn btn-primary btn-lg" href="https://www.facebook.com/messages/t/100002140432496" role="button">Tudj meg mégtöbbet!</a>
        </div>

        <div class="row">
            <div class="col-md-6 container">
                <div class="jumbotron" style="background-color:rgba(100, 212, 133, 0.89)">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;">E5N Előadás Jelentkezés</h1>
                    <p class="lead"><a class="btn btn-primary btn-lg" href="/e5n/presentations" role="button">Tovább a Jelentkezéshez!</a></p>
                    <hr class="my-4">
                    <p>Egy teljes átírást követően alfa verzióban</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="https://www.facebook.com/groups/E5vosDO" role="button">Tudj meg többet!</a>
                        <a class="btn btn-danger btn-lg" href="https://www.facebook.com/messages/t/100022524080676" role="button">Írd meg észrevételeid!</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 container" >
                <div class="jumbotron" style="background-color:rgba(82, 82, 100, 0.89)">
                    <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Hamarosan Bővül az oldal</h1>
                    <p class="lead"><a class="btn btn-warning btn-lg" href="https://www.facebook.com/messages/t/100022524080676" role="button">Jelentkezz fejlesztőnek!</a></p>
                    <hr class="my-4">
                    <p>A DÖ Honlap egy teljes átírást követően alfa verzióban</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="https://www.facebook.com/messages/t/100002140432496" role="button">Tudj meg többet!</a>
                        <a class="btn btn-danger btn-lg" href="https://www.facebook.com/messages/t/100022524080676" role="button">Írd meg észrevételeid!</a>
                    </p>
                </div>
            </div>
        </div class="row">

    </div>
</div>
@endsection


