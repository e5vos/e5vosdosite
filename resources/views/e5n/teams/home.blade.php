@extends('layouts.app')

@section('title')
Csapatok - E5N
@endsection


@section('content')

<div class="card container">
    <h1 class="text-center" style="font-size:32px;">A Te csapataid</h1>
    <div class="table-responsive-sm">
        <table class="table text-center">
            <thead>
                <tr>
                    <th scope="col">Csapatnév</th>
                    <th scope="col">Csapatkód</th>
                    <th scope="col">Tagok</th>
                    <th scope="col">Kezelés</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fasszopók</td>
                    <td>viragotagechi</td>
                    <td>4</td>
                    <td>
                        <button class="btn btn-info">Kezelés</button>
                        <button class="btn btn-danger">Kilépés</button>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th scope="row">Új csapat</th>
                    <td><input type="text" class="text" maxlength="13" placeholder="Példa Team"></td>
                    <td><input type="text" class="text" maxlength="13" placeholder="apeldateamkod"></td>
                    <td colspan="2"><button class="btn btn-primary" type="submit">Új csapat</button></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection
