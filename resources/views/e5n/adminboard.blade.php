@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
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
                        <div class="col-md-6 col-xl-4 card">
                            <div class="card-header">Kötélhúzás <span class="badge badge-success">OK</span></div>
                            <div class="card-body" style="text-align:center;">
                                <p>12:00-13:00</p>
                                <p><span class="text-info">Közpes</span> látogatottság: 2.3 scan/perc, összesen 7</p>
                                <p>6 szervező</p>
                                <p class="text-info">Tornacsarnok</p>
                                <p><img style="max-height:150px; max-width:300px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d8/Irish_600kg_euro_chap_2009.JPG/350px-Irish_600kg_euro_chap_2009.JPG" /></p>
                                <button class="btn btn-primary">Send message</button>
                            </div>
                        </div> 
                        <div class="col-md-6 col-xl-4 card">
                            <div class="card-header">12 órás StarCraft <span class="badge badge-success">OK</span></div>
                            <div class="card-body" style="text-align:center;">
                                <p>06:00-18:00</p>
                                <p><span class="text-info">Hatalmas</span> látogatottság: 23 scan/perc, összesen 1000</p>
                                <p>2 szervező</p>
                                <p class="text-info">17</p>
                                <p><img style="max-height:150px; max-width:300px;" src="https://upload.wikimedia.org/wikipedia/en/thumb/2/20/StarCraft_II_-_Box_Art.jpg/220px-StarCraft_II_-_Box_Art.jpg" /></p>
                                <button class="btn btn-primary">Send message</button>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4 card">
                            <div class="card-header">Konspi  <span class="badge badge-danger">Danger</span></div>
                            <div class="card-body" style="text-align:center;">
                                <p class="badge badge-danger" style="font-size:24px">Help Requested</p>
                                <p>10:00-16:00</p>
                                <p><span class="text-info">Közepes</span> látogatottság: 2 scan/perc, összesen 87</p>
                                <p>4 szervező</p>
                                <p class="text-info">207</p>
                                <p><img style="max-height:150px; max-width:300px;" src="https://cdn.infotagion.com/uploads/2020/05/thought-catalog-gbQ3EsFSdG8-unsplash-950x500.jpg" /></p>
                                <button class="btn btn-primary">Send message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
                                    <tr><td>2014A</td><td>1000</td></tr>
                                    <tr><td>2015A</td><td>1</td></tr>
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
