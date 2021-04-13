@extends('home')

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
                            Lorem ipsum
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
                                <tr>
                                    <td>7.A</td>
                                    <td>50 pont</td>
                                </tr>
                            </tbody>
                        </table>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="display-4">Map placeholder @svg ('map') </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
