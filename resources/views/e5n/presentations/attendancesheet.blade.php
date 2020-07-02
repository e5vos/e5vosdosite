@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <div class="py-2 container-fluid" style="background-color:rgba(255, 255, 255, 0.5)">
                <table class="table table-light" style="text-align:center;width:100%;table-layout: auto;">
                    <thead class="thead-dark">
                        <tr><th colspan=3>Kovács János kalandjai Madagaszkáron</th></tr>
                        <tr>
                            <th scope="col">Osztály</th>
                            <th scope="col" colspan=2>Név</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 10; $i++)
                        <tr>
                            <td>12.A</td>
                            <td>János István</td>
                            <td><input type="checkbox" class="form-control"></td>
                        </tr>
                        @endfor
                        <tr><td colspan=3><button style="width:25%" class="btn btn-dark">OK</button></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>
@endsection
