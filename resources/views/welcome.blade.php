<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="container">
                <div class="row">
                    <div class="col-sn-3 bg-light" >
                        @if (count(Cart::getContent()))

                        <a href="{{route('cart.checkout')}}">VER CARRITO: <span class="badge btn-danger">{{count(Cart::getContent())}}</span></a>
                        @else
                           <p>Carrito vacio</p>
                        @endif

                    </div>
                    <div class="col-sn-10">

                        @forelse ($products as $item)
                            <div class="col-4 border p-5 nt-5 text-conter">
                                <h1>{{$item->name}}</h1>
                                <p>{{$item->price}}</p>

                                <form action="{{route('cart.add')}}" method="post">
                                    @csrf
                                <input type="hidden" name="product_id" value="{{$item->id}}">
                                <input type="submit" name="btn" class="btn btn-success" value="ADD TO CART">
                                </form>

                            </div>

                         @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
