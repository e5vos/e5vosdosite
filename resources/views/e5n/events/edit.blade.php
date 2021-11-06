@extends('layouts.app')

@section('title')
    E5N Esememényszerkesztő
@endsection

@section('content')
<div class="card container">
    <form action="{{route('event.update',$event->code)}}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Esemény kódja</label>
                    <input name="code" id="code" type="text" class="form-control" maxlength="13" value="{{$event->code}}">
                </div>

                <div class="form-group">
                    <label for="weight">Esemény Súlya</label>
                    <input type="number" name="weight" id="weight" class="form-control" value="{{$event->weight}}">
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
                    <select name="location" id="location" class="form-control">
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
                    <select name="slot" id="slot" class="form-control">
                        <option value="null">Sáv nélküli</option>
                        @for ($i = 1; $i <= 3; $i++)
                            <option value="{{$i}}" {{$event->slot==$i ? "selected" : ""}} >{{$i}}. sáv</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_presentation">Előadás?</label>
                    <input type="checkbox" name="is_presentation" id="is_presentation" class="form-control" {{$event->is_presentation ? "checked" : ""}}>
                </div>

                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class=" btn btn-primary">Változások mentése</button>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <h2>Szervezők</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col">Email</th>
                            <th scope="col">Osztály</th>
                            <th scope="col">Kezelés</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($event->organisers()->get() as $orga)
                            <tr>
                                <td>{{$orga->name}}</td>
                                <td>{{$orga->email}}</td>
                                <td>{{$orga->ejgClass ? $orga->ejgClass->name : "Nem elérhető"}}</td>
                                <td><button class="btn btn-danger" {{ \Auth::user()->is($orga) ? "disabled" : ""}}>Eltávolítás</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr>
                            <th scope="row"> Új szervező</th>
                            <td colspan="2"><input type="email" name="" id="" class="form-control"></td>
                            <td><button class="btn btn-primary">Hozzáadás</button></td>
                        </tr>

                    </tbody>

                </table>
            </div>
            <div class="col-md-2">
                <h3>Egyéb</h3>
                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-primary">Esemény oldala</button>
                    <button type="button" class="btn btn-secondary">Eseménykezelő</button>
                    <button type="button" class="btn btn-primary">Statisztika</button>
                    <button type="button" class="btn btn-danger">Esemény törlése</button>
                </div>
            </div>
        </div>


    </form>
</div>
@endsection
