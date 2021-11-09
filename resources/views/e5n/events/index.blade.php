@extends('layouts.app')

@section('title')
    E5N Programok
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <div class="jumbotron">
                <h1 style="text-align:center;font-size:32px;">Programok</h1>
                <p style="text-align: center">Válassz napot!</p>
            </div>
            <br/>
            <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
                @php($i=0)
                @foreach ($events as $dayEvents)
                    <button data-switchgroup="switch-table1" data-target="{{$i}}" type="button" class="btn btn-secondary table-switcher">{{$dayEvents[0]->start ? $dayEvents[0]->format("l") : "Ismeretlen"}}</button>
                    @php($i++)
                @endforeach
            </div>
            @foreach ($events as $dayEvents)
            <table class="switch-table1 table table-light table-striped table-bordered switch-table" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word; border:1px;display:none;">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Program neve</th>
                        <th scope="col">Program kezdete</th>
                        <th scope="col">Program vége</th>
                        <th scope="col">Helyszín</th>
                        <th scope="col">Értékelés</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dayEvents as $event)
                        <tr>
                            <td><a href="/e5n/event/{{$event->code}}">{{$event->name}}</a></td>
                            <td>{{$event->start ? $event->start->format('H:i') : "Ismeretlen"}}</td>
                            <td>{{$event->end ? $event->end->format('H:i') : "Ismeretlen"}}</td>
                            @if ($event->location != null)
                                <td>{{$event->location->name}}</td>
                            @else
                                <td>{{__('e5n.no_location')}}</td>
                            @endif
                        <td>{{$event->rating}}/10</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
        </div>
    </div>
@endsection
