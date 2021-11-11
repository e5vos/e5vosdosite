@extends('layouts.app')

@section('title')
    E5N van, örvendjetek
@endsection

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="display-4">Eötvös Napok</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="display-4"> Eötvös Napok</h2>
                        <p class="card-text">
                            Helyezések
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
               <div class="card">
                    <div class="card-body text-center">
                    <h2 class="display-4">Helyezés<h2>
                    <table class="table">
                            <tbody>
                                @foreach($classes as $class)
                                <tr>
                                    <td>{{$class->name}}</td>
                                    <td>{{$class->points}} pont</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="display-4">Térkép helye @svg ('far-map') </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
