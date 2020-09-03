@extends('layouts.app')

@section('content')
    <div class="container" style="background-color:rgba(255, 255, 255, 0.5)">
        <h1 style="text-align:center;font-size:32px;">Előadássávok - Tanári adminisztrációs felület</h1>
        <div style="font-size:20px;color:black;background-color:rgba(255,255,255,0.75)">
            <p>Használati utasítás Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et egestas felis. Morbi elit orci, auctor vel pharetra vitae, bibendum nec quam. Phasellus eu purus feugiat, commodo nibh a, fringilla ex. Fusce facilisis turpis tincidunt pellentesque sodales. Aliquam sed velit quis ligula congue commodo et a libero. Maecenas semper erat ligula, et elementum urna gravida eu. Integer fermentum erat viverra mollis fermentum. Donec semper, lorem sed iaculis tristique, nisi quam posuere neque, eget imperdiet tortor purus non mauris. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
            <p>Duis id pellentesque massa, rhoncus varius risus. Maecenas fermentum porttitor arcu, blandit tempor libero ullamcorper ac. Proin imperdiet enim eget augue imperdiet, id convallis augue semper. Nullam vel sagittis dui. Aliquam est est, bibendum a pellentesque eget, sollicitudin a dolor. Ut volutpat eu lorem at ornare. Nunc mattis semper velit quis ornare. In hac habitasse platea dictumst. Nulla mollis dui velit, quis pulvinar sem lacinia sed. Vestibulum ut diam vitae velit scelerisque aliquam.</p>
            <p>Nulla sollicitudin eros eget urna efficitur malesuada. Vestibulum sed sem in ex volutpat tincidunt eu id elit. Suspendisse vel odio eget orci iaculis iaculis pretium porta mi. In dapibus quam ac consequat vehicula. Etiam sit amet purus est. Etiam ultricies auctor sem, ac scelerisque magna volutpat eget. Vestibulum a libero euismod, ultrices neque eu, euismod elit. In et posuere mi. Morbi vitae quam iaculis, porta libero sit amet, gravida lectus. Cras tellus tellus, interdum vehicula nunc at, consectetur porttitor odio.</p>
        </div>
        <nemjelentkezett></nemjelentkezett>

        <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;">
            <thead class="thead-dark">
                <tr><th scope="col" colspan="5">Előadások</th></tr>
                <tr>
                    <th scope="col" rowspan="2">Előadó</th>
                    <th scope="col" rowspan="2">Előadás címe</th>
                    <th scope="col">Foglalt helyek</th>
                    <th scope="col">Szabad helyek</th>
                    <th scope="col" rowspan="2">Megjelent</th>
                </tr>
                <tr>
                    <th colspan="2"><button class="btn btn-warning">Automatikus feltöltés</button></th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 10; $i++)
                <tr>
                    <td>A Jani</td>
                    <td>János legyen, fenn a János-hegyen</td>
                    <td>25</td>
                    <td><button class="btn btn-warning">5</button></td>
                    <td>25</td>
                </tr>
                @endfor
            </tbody>
        </table>
        <br/>
    </div>
@endsection
