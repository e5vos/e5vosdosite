@extends('layouts.app')

@section('title')
    E5N Programok
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="jumbotron">
                <p>Használati utasítás: <br/> Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi impedit repellendus, a minus, cupiditate iste ab repellat ipsa pariatur magni dicta eum itaque dignissimos. Eos doloremque debitis molestias sint iusto!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <events-table/>
            </div>
        </div>
    </div>
@endsection
