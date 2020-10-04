@extends('layouts.app')


   @section('content')
        <div class="container">
            <div class="row">
                <div class="col-sn-12 bg-light" >
                    @if (count(Cart::getContent()))

                   <table class="table table-striped">
                       <head>
                           <th>ID</th>
                           <th>Nombre</th>
                           <th>Precio</th>
                       </head>
                       <body>
                          @foreach (Cart::getContent() as $item)
                          <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->price}}</td>
                          </tr>
                          @endforeach
                       </body>

                   </table>

                    @endif
                  </div>
        </div>
   @endsection


