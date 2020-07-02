@extends('home')

@section('title')
    E5N van, örvendjetek
@endsection

@section('content')
    <div class="container-fluid" >
        <div class="row">

            <!-- Left Sidebar -->

            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 border border-dark" >
                <div class="row border border-dark">
                        <h1>Jelenleg futó programok</h1>
                </div>
                <div class="comtainer-fluid">
                    <div class="row border border-dark">
                        <h2>Programnév</h2>
                    </div>
                    <div class="row border border-dark">
                        <div class="col-3 border border-dark align-middle" style="padding-top: 40px">
                            <div class="row border border-dark">
                                <h3> 000 </h3>
                            </div>
                            <div class="row border border-dark">
                                <h5> Terem </h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="{{url('/images/default.png')}}" alt="" width="100%">
                        </div>
                        <div class="col-3 border border-dark align-middle" style="padding-top: 30px">
                            <div class="row border border-dark">
                                <h5> 00:00-tól <br/> 00:00-ig </h5>
                            </div>
                            <div class="row border border-dark">
                                <h3> Időpont </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comtainer-fluid">
                    <div class="row border border-dark ">
                        <ul class="pager ">
                            <a class="previous" href="#"><button class="btn btn-info">Előző</button></a>
                            <a class="next" href="#"><button class="btn btn-info">Következő</button></a>
                        </ul>
                    </div>
                    <div class="row border border-dark">
                        <!-- slideshow -->
                    </div>
                </div>
            </div>

            <!-- Middle content (map) -->

            <div class="col-lg-5 col-md-9 col-sm-12 col-xs-12 border border-dark">

            </div>

            <!-- Right Sidebar -->

            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 border border-dark">
                <div class="row" style="padding-top: 6.9%;">
                    <span class="border border-dark align-center"> <h2> Uname </h2> </span>
                </div>
                <div class="row">
                    <h4 class="border border-dark align-center">Pontok </h4>
                </div>
                <div class="container border border-dark">
                    <div class="row">
                        <h3>program</h3> <h3>Pontok: xx</h3>
                    </div>
                    <div class="row">
                        <h3>program</h3> <h3>Pontok: xx</h3>
                    </div>
                    <div class="row">
                        <h3>program</h3> <h3>Pontok: xx</h3>
                    </div>
                    <div class="row">
                        <h3>program</h3> <h3>Pontok: xx</h3>
                    </div>
                </div>
                <div class="row ">
                    <span class="border border-dark"> osztálypont : xxx <br/>
                    általad szerzett : xxx</span>
                </div>
            </div>
        </div>
    </div>
@endsection
