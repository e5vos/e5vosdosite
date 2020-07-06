@extends('layouts.app')

@section('title')
    Szervusz nagyúr
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="jumbotron" style="background-color: rgba(47, 79, 79, 0.89);">
                    <table class="table table-light">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="3">
                                    <input type="text" class="form-control">
                                </th>
                                <th colspan="2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="1">Osztály</th>
                                <th colspan="1">Név</th>
                                <th colspan="3">Jogosultságok</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="overflow-auto" style="width: 100%;background-image: ;height:380px;">
                        <table class="table table-light" style="text-align:center;width:100%;table-layout: auto;background-color: darkslategrey;">

                            <tbody>
                                @for ($i = 0; $i < 10; $i++)
                                <tr style="color: rgba(255, 255, 255, 0.89)">
                                    <td>12.A</td>
                                    <td id="uname1">János István</td>
                                    <td>
                                        <label for="e5n">E5N</label>
                                        <input type="checkbox" class="form-control" id="e5n">
                                    </td>
                                    <td>
                                        <label for="szalag">szalag</label>
                                        <input type="checkbox" class="form-control" id="szalag">
                                    </td>
                                    <td>
                                        <label for="admin">admin</label>
                                        <input type="checkbox" class="form-control" id="admin">
                                    </td>
                                    <td><button id="" class="btn btn-info" onclick="DM('1')">DM</button></td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="jumbotron" style="background-color: rgba(47, 79, 79, 0.89);">
                    <table class="table table-light">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="7" style="text-align: center"> Segélykiáltások </th>
                            </tr>
                            <tr style="text-align: center">
                                <th style="width: 10%">név</th>
                                <th style="width: 40%">Problem</th>
                                <th>
                                    <button class="btn btn-primary" onclick="conf('Mindet &#1F441')">Mindet &#1F441</button>
                                </th>
                                <th>
                                    <button class="btn btn-primary"  onclick="conf('Mindet &#2705')">Mindet &#2705</button>
                                </th>
                                <th>
                                    <button class="btn btn-primary"  onclick="conf('Mindet &#26D4')">Mindet &#26D4</button>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="overflow-auto" style="width: 100%;background-image: ;height:380px;">
                        <table class="table table-light" style="text-align:center;width:100%;table-layout: auto;background-color: darkslategrey;">

                            <tbody>
                                @for ($i = 0; $i < 10; $i++)
                                <tr style="color: rgba(255, 255, 255, 0.89)">
                                    <td>János István</td>
                                    <td>kakilni kell</td>
                                    <td>
                                        <button class="btn btn-link"><span>&#1F441</span></button>
                                    </td>
                                    <td>
                                        <button class="btn btn-link">&#2705</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-link">&#26D4</button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="jumbotron" style="background-color: rgba(0, 0, 0, 0.89);">
                        <button class="btn btn-success btn-lg btn-block" onclick="confImportant('Start E5N')">Start E5N</button>
                        <button class="btn btn-danger btn-lg btn-block" onclick="confImportant('End E5N')">End E5N</button>
                        <button class="btn btn-danger btn-lg btn-block" onclick="confImportant('E5N Reset')">E5N Reset</button>
                        <button class="btn btn-danger btn-lg btn-block" onclick="confImportant('Service Maintenance')">Service Maintenance</button>
                </div>
                <div class="jumbotron" style="background-color: rgba(0, 0, 0, 0.89);">
                    <div class="input-grup">
                        <textarea class="form-control" rows="3" maxlenght="169" id="comment">Közlemény a főoldalra</textarea>
                        <button class="btn btn-info btn-block" onclick="confMessage(document.getElementById('comment').value)">Küldés</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

    function DM(uname) {
        uname=document.getElementById("uname1").innerText;
        if(msg=window.prompt(uname + ' DM', 'üzenj neki')) {
            confMessage(msg);
        }
    }
    function conf(USSR) {
        window.confirm("Bizto vagy ebben: " + USSR +"?")
    }
    function confImportant(SWR) {
        for(var i=0; i<=5; i++) {
            if (!window.confirm("Bizto bagy ebben: " + SWR +"?")) {
                break;
            }
        }
    }
    function confMessage(CCCP) {
        confirm('Biztos elküldöd ezt: ' + CCCP + '?');
    }
</script>
@endsection
