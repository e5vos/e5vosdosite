@extends('layouts.app')

@section('title')
    E5N Előadásszerkesztő
@endsection

@section('content')
<div class="card container">
    <form action="{{route(presentation.store)}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="slot">Előadássáv</label>
            <select name="slot" id="slot" class="form-control">
                <option value="">1.sáv</option>
                <option value="">2.sáv</option>
                <option value="">3.sáv</option>
            </select>
        </div>

        <div class="form-group">
            <label for="code">Előadás kódja</label>
            <input name="code" id="code" type="text" class="form-control" maxlength="13" >
        </div>

        <div class="form-group">
            <label for="title">Előadás címe</label>
            <input name="title" id="title" type="text" class="form-control" >
        </div>

        <div class="form-group">
            <label for="presenter_name">Előadó(k) neve(i)</label>
            <input name="presenter_name" id="presenter_name" type="text" class="form-control" >
        </div>

        <div class="form-group">
            <label for="description">Előadás rövid leírása</label>
            <input type="text" name="description" id="description" class="form-control" >
        </div>

        <div class="form-group">
            <label for="max_capacity">Max létszám</label>
            <input type="number" name="max_capacity" id="max_capacity" class="form-control" >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Változások mentése</button>
        </div>
    </form>
</div>
@endsection
