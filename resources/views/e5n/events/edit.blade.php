@extends('layouts.app')

@section('title')
    E5N Esememényszerkesztő
@endsection

@section('content')
<div class="card container">
    @if ($event->trashed() && \Auth::user()->isAdmin())
        <div class="card bg-danger align-middle" style="font-size: 24px; text-align: middle;"><strong>Az Esemény törölve lett, visszaállítás a jobb alsó sarokban lévő gombbal</strong></div>
    @endif
    <form action="{{route('event.update',$event->code)}}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Esemény kódja</label>
                    <input name="code" id="code" type="text" class="form-control" maxlength="13" value="{{$event->code}}" {{Auth::user()->isAdmin() ? "" : "disabled"}}>
                </div>

                <div class="form-group">
                    <label for="weight">Esemény Súlya</label>
                    <input type="number" name="weight" id="weight" class="form-control" value="{{$event->weight}}" {{Auth::user()->isAdmin() ? "" : "disabled"}}>
                </div>

                <div class="form-group">
                    <label for="name">Esemény neve</label>
                    <input name="name" id="name" type="text" class="form-control" value="{{$event->name}}">
                </div>

                <div class="form-group">
                    <label for="description">Rövid leírás</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{$event->description}}">
                </div>
                <div class="form-group">
                    <label for="name">Esemény helye</label>
                    <select name="location" id="location" class="form-control" {{Auth::user()->isAdmin() ? "" : "disabled"}}>
                        <option value="">Nincs helye</option>
                        @foreach (\App\Location::all() as $location )
                            <option value="{{$location->id}}" {{ $event->location->id==$location->id ? "selected" : ""}}>{{$location->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="start">Esemény kezdete:</label>
                    <input type="datetime" name="start" id="start" class="form-control" value="{{$event->start}}">
                </div>

                <div class="form-group">
                    <label for="start">Esemény vége:</label>
                    <input type="datetime" name="end" id="end" class="form-control" value="{{$event->end}}">
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="organiser_name">Szervező(k) neve(i)</label>
                    <input name="organiser_name" id="organiser_name" type="text" class="form-control" value="{{$event->organiser_name}}">
                </div>


                <div class="form-group">
                    <label for="capacity">Max létszám</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" value="{{$event->capacity}}">
                </div>

                <div class="form-group">
                    <label for="slot">Sáv</label>
                    <select name="slot" id="slot" class="form-control" {{Auth::user()->isAdmin() ? "" : "disabled"}}>
                        <option value="null">Sáv nélküli</option>
                        @for ($i = 1; $i <= 3; $i++)
                            <option value="{{$i}}" {{$event->slot==$i ? "selected" : ""}} >{{$i}}. sáv</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_presentation">Előadás?</label>
                    <input type="checkbox" name="is_presentation" id="is_presentation" class="form-control" {{$event->is_presentation ? "checked" : ""}} {{Auth::user()->isAdmin() ? "" : "disabled"}}>
                </div>

                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class=" btn btn-primary">Változások mentése</button>
                </div>

            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-10">
            <h2>Szervezők</h2>
            <div class="container-fluid">
                <div class="row bg-dark rounded border border-light" style="color: whitesmoke">
                    <div class="col-3 border-right border-light">Név</div>
                    <div class="col-5 border-right border-light">E-mail</div>
                    <div class="col-2 border-right border-light">Osztály</div>
                    <div class="col-2">Kezelés</div>
                </div>
                <form action="{{route('event.organisers.remove', $event->code)}}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE"/>
                    @foreach($event->organisers()->get() as $orga)
                        <div class="row bg-light rounded border border-dark">
                            <div class="col-3 border-right border-dark">{{$orga->name}}</div>
                            <div class="col-5 border-right border-dark">{{$orga->email}}</div>
                            <div class="col-2 border-right border-dark">{{$orga->ejgClass ? $orga->ejgClass->name : "Nem elérhető"}}</div>
                            <div class="col-2"><button type="submit" name="orga" value="{{$orga->id}}" class="btn btn-danger" {{ \Auth::user()->is($orga) || \Auth::user()->isAdmin() ? "" : "disabled"}}>Eltávolítás</button></div>
                        </div>
                    @endforeach
                </form>
                <form action="{{route('event.organisers.add', $event->code)}}" method="POST">
                    @csrf
                    <div class="row bg-dark rounded border border-light input-group w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Új szervező</span>
                        </div>
                        <input type="email" name="email" class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Hozzáadás</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <h3>Egyéb</h3>
            <div class="btn-group-vertical">
                <a href="/e5n/event/{{$event->code}}" class="btn btn-primary">Esemény oldala</a>
                <a href="/e5n/event" class="btn btn-secondary">Eseménykezelő</a>
                <a href="/e5n/event/{{$event->code}}/statistics" class="btn btn-primary">Statisztika</a>

                @if (!$event->trashed())
                    <form action="{{route('event.destroy', $event->code)}}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">Esemény törlése</button>
                    </form>
                @elseif(\Auth::user()->isAdmin())
                    <form action="{{route('event.restore', $event->code)}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Esemény visszaállítása</button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
