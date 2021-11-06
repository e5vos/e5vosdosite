@extends('layouts.app')

@section('title')
    Program menedzsment
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-light table-striped table-bordered switch-table" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
                    <thead class="thead thead-dark">
                        <th>
                            Saját Programjaim
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($myEvents as $event)
                        <tr>
                            <td><a href="/e5n/events/{{$event->id}}/edit"><button class="btn btn-primary">{{$event->name}} szerkesztése</button></a></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="jumbotron">
                    <button class="btn btn-success btn-lg"><a href="#">Új esemény</a></button>
                </div>
            </div>
        </div>
    </div>
@endsection
