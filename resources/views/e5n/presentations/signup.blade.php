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
        <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
            <button type="button" class="btn btn-success active">1. előadássáv</button>
            @for ($i = 2; $i <= 3; $i++)
                <button type="button" class="btn btn-danger">{{$i}}. előadássáv</button>
            @endfor
        </div>
        <br/>
        <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Előadó</th>
                    <th scope="col">Előadás címe</th>
                    <th scope="col">Előadás rövid leírása</th>
                    <th scope="col">Szabad helyek száma</th>
                </tr>
            </thead>
            <tbody>
                <div>
                    <tr>
                        <td colspan="4" style="font-weight:600;font-size:24px;">Az általad választott előadás:</td>
                    </tr>
                    <tr style="background-color:rgb(233, 233, 233)">
                        <td>A Jani</td>
                        <td>János legyen, fenn a János-hegyen</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla pharetra imperdiet nulla quis rutrum. Cras eu diam venenatis, semper urna eu, tristique neque. Integer gravida purus id est egestas, et dapibus orci commodo. Nunc pellentesque urna nulla, eu sollicitudin mi fermentum sit amet. Aliquam pretium nisl tortor, vel dictum dui interdum ac. Aenean in risus sed enim mattis mattis. Aliquam vel mauris porttitor, hendrerit sem ac, elementum neque. Nam eu leo diam. Ut iaculis orci enim. Maecenas pretium aliquet pharetra. Vivamus semper imperdiet leo, sed aliquet odio posuere ac.</td>
                        <td><button class="btn btn-secondary" disabled>5</td>
                    </tr>
                    <tr><td colspan=4><button class="btn btn-danger">Jelentkezés törlése</button></td></tr>
                    <tr><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px solid black;"></td></tr>
                </div>
                @for ($i = 0; $i < 10; $i++)
                <tr>
                    <td>A Jani</td>
                    <td>János legyen, fenn a János-hegyen</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla pharetra imperdiet nulla quis rutrum. Cras eu diam venenatis, semper urna eu, tristique neque. Integer gravida purus id est egestas, et dapibus orci commodo. Nunc pellentesque urna nulla, eu sollicitudin mi fermentum sit amet. Aliquam pretium nisl tortor, vel dictum dui interdum ac. Aenean in risus sed enim mattis mattis. Aliquam vel mauris porttitor, hendrerit sem ac, elementum neque. Nam eu leo diam. Ut iaculis orci enim. Maecenas pretium aliquet pharetra. Vivamus semper imperdiet leo, sed aliquet odio posuere ac.</td>
                    <td><button class="btn btn-success" disabled>5</button></td>
                </tr>
                @endfor
            </tbody>
        </table>
        <br/>
    </div>
@endsection
