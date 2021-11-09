@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9" style="color: lightblue">
            <div class="card">
                <div class="card-header">Aktív programok</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <label for="message" class="col-md-4 col-form-label text-md-right">Message:</label>

                                <div class="col-md-6">
                                    <input id="message" type="text" class="form-control" name="message">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary">Send to all</button>
                                </div>
                            </div>
                        </div>

                        @foreach($events as $event)
                            <div id="event#{{$event->id}}" class="col-md-6 col-xl-4 card">
                                <div class="card-header">{{$event->name}} <span class="badge badge-{{$event->badgeClass}}">{{$event->badgeMessage}}</span></div>
                                <div class="card-body" style="text-align:center;">
                                    <p>{{$event->type}} event</p>
                                    <p>{{$event->start->format('h:i')}}-{{$event->end->format('h:i')}}</p>
                                    <p><span class="text-info">{{$event->visitorcount()}}</span> visitors</p>
                                    <p>{{$event->permissions->count()}} organisers</p>
                                    <p class="text-info">{{$event->location_id}}. terem.</p>
                                    @if ($event->image())
                                        <p><img style="max-height:150px; max-width:300px;" src="{{Storage::url($event->image()->location)}}" /></p>
                                    @endif
                                    <button onclick="msg({{$event->id}})"class="btn btn-primary">Send message</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="color: lightblue">
            <div class="card">
                <div class="card-header">Oldalsáv</div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">Előadások jelenleét</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr><th>Előadás</th><th>Hiányzók</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Ezek a sponzorok</td><td>200</td></tr>
                                    <tr><td>Tisza Japán</td><td>0</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Eötvös Napok Pontállás</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr><th>Osztály</th><th>Pont</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($classRanks as $classRank)

                                        <div id="classid#{{$classRank->id}}">
                                            <tr><td>{{$loop->index+1}}.</td><td>{{$classRank->name}}</td><td>{{$classRank->points}}</td></tr>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
