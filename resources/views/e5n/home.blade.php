@extends('home')

@section('title')
    E5N van, örvendjetek
@endsection

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-md-5">
                <div class="jumbotron">
                    <h2 class="display-4"> Eötvös Napok</h1>
                    <p class="card-text">
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab, harum. Incidunt, repellendus sunt accusamus aliquid deleniti, reiciendis ut architecto tempore quam, ipsum iusto sed nobis aut ipsam eaque. Sunt, quisquam?
                    </p>
                </div>
            </div>
            <div class="col-md-3">
               <div class="jumbotron">
                   <h2 class="display-4">Helyezés<h2>
                   <table class="table">
                        <tbody>
                            <tr>
                                <td>7.A</td>
                                <td>50 pont</td>
                            </tr>
                        </tbody>
                    </table>
               </div>
            </div>
            <div class="col-md-4">
                <div class="jumbotron">
                    <h2 class="display-4">Map placeholder @svg ('map') </h1>
                </div>
            </div>
        </div>
    </div>



@endsection
