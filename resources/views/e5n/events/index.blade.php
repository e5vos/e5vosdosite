@extends('layouts.app')

@section('title')
    E5N Programok
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <div class="jumbotron">
                <h1 style="text-align:center;font-size:32px;">Programok</h1>
            </div>
            <br/>
            <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
                @foreach ($events as $day => $dayEvents)
                    <button data-switchgroup="switch-table1" data-target="{{$day == 0 ? 6 : $day-1}}" type="button" class="btn btn-secondary table-switcher">{{$dayEvents[0]->start->format("l")}}</button>
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
                            <td>{{\Carbon\Carbon::parse($event->start)->format('H:i')}}</td>
                            <td>{{\Carbon\Carbon::parse($event->end)->format('H:i')}}</td>
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
