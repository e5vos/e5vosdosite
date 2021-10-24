@extends('app.layout')

@section('title')
    Program menedzsment
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
        <p>Használati utasítás    Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi impedit repellendus, a minus, cupiditate iste ab repellat ipsa pariatur magni dicta eum itaque dignissimos. Eos doloremque debitis molestias sint iusto!</p>
        </div>
        <div class="row">
            <div class="col-md-9">
                <my-events-table/>
            </div>
            <div class="col-md-3">
                <div class="jumbotron">
                    <button class="btn btn-success btn-lg"><a href="#">Új esemény</a></button>
                </div>
            </div>
        </div>
    </div>
@endsection
