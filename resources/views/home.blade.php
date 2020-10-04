@extends('layouts.app')

@section('content')
<div class="container">
    < div class="row justify-content-center">
        @forelse ($products as $item)
         <div class="col-4 border p-5 nt-5 text-conter">
             <h1>{{$item->name}}</h1>
             <p>{{$item->price}}</p>

             <form action="{{route('cart.add')}}">

             </form>

         </div>

        @empty

         @endforelse

    </div>
</div>
@endsection
