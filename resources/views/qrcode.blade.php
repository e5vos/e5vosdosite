@extends('layouts.app')

@section('title')
QR Kód - Eötvös DÖ
@endsection

@section('content')

<div class="container-fluid" style="color:white">
    <div class="window-content">
        <div class="jumbotron" style="background-color:rgba(51, 3, 185, 0.89)">
            <h1 class="display-4"><img src="{{asset('images/defaultdonci.png')}}" style="height:1em;"> Üdv az új DÖ Honlapon!</h1>
            <div class="qr-code">
                {!! QrCode::size(500)->generate($code); !!}
            </div>
        </div>
    </div>
</div>
@endsection


