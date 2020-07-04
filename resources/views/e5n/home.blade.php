@extends('home')

@section('title')
    E5N van, örvendjetek
@endsection

@section('content')
    <div class="container-fluid" >
        <div class="row">

            <!-- Left Sidebar -->

            <div class="col-md-4">
                <div class="jumbotron" style="background-color: rgba(100, 149, 237, 0.89)">
                    <div class="row">
                        <h1>Jelenleg futó programok</h1>
                    </div>
                    <div class="row border border-left-0 border-right-0 border-dark">
                        <h2>Programnév</h2>
                    </div>
                    <div class="row py-2">
                        <div class="col-3" >
                                <div class="row">
                                    <h2 style="width: 100%;text-align: center;padding-top:100%;"> 000 </h2>
                                </div>
                                <div class="row">
                                    <h4 style="width: 100%;text-align: center;padding-bottom: 100%;"> Terem </h4>
                                </div>
                        </div>
                        <div class="col-6">
                            <img src="{{asset('/images/default.png')}}" alt="" class="rounded" width="100%">
                        </div>
                        <div class="col-3" style="padding-top: 30px">
                            <div class="row">
                                <h5 style="width: 100%;text-align: center;padding-top:50%"> 00:00-tól <br/> 00:00-ig </h5>
                            </div>
                            <div class="row">
                                <h3 style="width: 100%;text-align: center;"> Időpont </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <ul class="pager ">
                            <a class="previous" href="#"><button class="btn btn-info">Előző</button></a>
                            <a class="next" href="#"><button class="btn btn-info">Következő</button></a>
                        </ul>
                    </div>
                    <div class="row">
                        <!-- slideshow -->
                    </div>
                </div>
            </div>

            <!-- Middle content (map) -->

            <div class="col-md-5">
                <div class="jumbotron" style="background-color: rgba(100, 149, 237, 0.89)">

                </div>
            </div>

            <!-- Right Sidebar -->

            <div class="col-md-3">
                <div class="jumbotron" style="background-color: rgba(100, 149, 237, 0.89)">
                    <div class="row" style="padding-top: 6.9%;">
                        <span class="align-center"> <h2> Uname pontjai </h2> </span>
                    </div>
                    <div class="row">
                        <h4 class="border border-right-0 border-left-0 border-dark" style="width: 100%;text-align:center;">Pontok</h4>
                    </div>
                    <div class="overflow-auto" style="width: 100%; height: 300px;">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="row">
                                <h4 style="text-align: center;width:90%"> program Pontok: x</h4>
                            </div>
                        @endfor
                    </div>

                    <div class="row">
                        <span class=""> osztálypont : xxx <br/>
                        általad szerzett : xxx</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
