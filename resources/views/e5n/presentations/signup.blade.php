@extends('layouts.app')

@section('content')
    <div class="container" style="background-color:rgba(255, 255, 255, 0.5)">
        <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>
        <div class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
            <div class="form-group">
                <label for="studentcode">Diákkód</label>
                <input class="form-control" type="text" id="studentcode" name="studentcode" maxlength="13"/>
            </div>
            <div class="form-group">
                <label for="omcode">OM-Kód utolsó 5 számjegye</label>
                <input class="form-control" type="number" id="omcode" name="omcode" maxlength="5"/>
            </div>
            <div class="form-group">
                <button class="btn btn-light">Bejelentkezés</button>
            </div>
        </div>
        <div class="container alert alert-danger py-2" style="width:25%;text-align:center;">
            <h3>Hibás bejeletkezési adatok</h3>
        </div>
        <div class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
            <h3>Kovács Béla, 12.a</h3>
        </div>

        <br/>
        <presentations></presentations>
        <br/>
    </div>
@endsection
