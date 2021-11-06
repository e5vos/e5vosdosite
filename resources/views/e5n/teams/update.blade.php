@extends('layouts.app')

@section('title')
Fasszopók - E5N Csapatok
@endsection


@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">Fasszopók - E5N Csapatok</h1>
    <div><button class="btn btn-success"> Meghívás elfogadása</button></div>
    <div class="table-responsive-md">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th scope="col">Név</th>
                    <th scope="col">Státusz</th>
                    <th scope="col">E-mail cím</th>
                    <th scope="col">Osztály</th>
                    <th scope="col">Kezelés</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Példa Karcsi</td>
                    <td>Csapatkezelő</td>
                    <td>pelda.karcsi@e5vos.hu</td>
                    <td>14.A</td>
                    <td>
                        <button class="btn btn-danger" disabled>Kirúgás</button>
                    </td>
                </tr>
                <tr>
                    <td>Példa Béla</td>
                    <td>Csapatkezelő</td>
                    <td>pelda.bela@e5vos.hu</td>
                    <td>14.A</td>
                    <td>
                        <button class="btn btn-danger">Kirúgás</button>
                    </td>
                </tr>
                <tr>
                    <td>Szopó János</td>
                    <td>Tag</td>
                    <td>szopo.jani@e5vos.hu</td>
                    <td>14.C</td>
                    <td>
                        <button class="btn btn-danger">Kirúgás</button>
                    </td>
                </tr>
                <tr>
                    <td>Példa Kristóf</td>
                    <td>Meghívva</td>
                    <td>pelda.kristof@e5vos.hu</td>
                    <td>Ismeretlen</td>
                    <td>
                        <button class="btn btn-danger">Kirúgás</button>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th scope="row">E-mail cím:</th>
                    <td colspan="2"><input type="email" name="" id="" placeholder="pelda.bela@e5vos.hu"></td>
                    <td><button class="btn btn-info" type="submit">Tag hozzáadása</button></td>
                    <td><button class="btn btn-danger"> Kilépés a csapatból</button></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection
