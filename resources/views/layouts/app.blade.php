<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap4.min.css" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    
    
@if(session()->has('type') && session('type') == "admin")
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $( document ).ready(function() {
            $('.data-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": {
                "url": '{{ route("datatables-inicial") }}',
                "type": "POST"
                },
                "responsive": true,
                "pageLength": 10,
                "columns": [
                                {     "data"     :     "id"  },  
                                {     "data"     :     "name"},  
                                {     "data"     :     "email"}, 
                                {     "data"     :     "action"}, 
                                {     "data"     :     "action1"},
                ],
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "language": {
                            "url": "{{url('/')}}/json/{!! trans('messages.lang3') !!}.json"
                }
            } );
        });
        function addus()
        {
            $('#titulogenerico').html("{!! trans('messages.lang4') !!}");
            $('#bodygenerico').html('<form method="POST" action="{{ route('registro-de-usuario') }}" ><input type="hidden" name="_token" value="{{ csrf_token() }}" /><div class="row mb-3"><label for="name" class="col-md-4 col-form-label text-md-end">{!! trans('messages.lang6') !!}</label><div class="col-md-6"><input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>@error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div><div class="row mb-3"><label for="email" class="col-md-4 col-form-label text-md-end">Email</label><div class="col-md-6"><input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div><div class="row mb-3"><label for="password" class="col-md-4 col-form-label text-md-end">{!! trans('messages.lang7') !!}</label><div class="col-md-6"><input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password"> @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div><div class="row mb-3"><label for="password-confirm" class="col-md-4 col-form-label text-md-end">{!! trans('messages.lang8') !!}</label><div class="col-md-6"><input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"></div></div><div class="row mb-0"><div class="col-md-6 offset-md-4"><button type="submit" class="btn btn-primary">{!! trans('messages.lang4') !!}</button></div></div></form>');
            $('#footergenerico').html('<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarmodal()">{!! trans('messages.lang5') !!}</button>');
            $('#modalgenerico').modal("show");
        }
        function editarus(id)
        {
            //$('#titulogenerico').html("{!! trans('messages.lang4') !!}");
            //$('#bodygenerico').html("");
            //$('#footergenerico').html("");
            //$('#modalgenerico').modal("show");
            var form = new FormData();
            form.append("_token", "{{ csrf_token() }}");
            form.append("id", id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{route("editar-usuario")}}',
                processData: false,
                contentType: false,
                dataType: "json",
                data: form,
                success: function(response){
                    console.log(response);

                    if(response.status == "ok")
                    {
                        $('#titulogenerico').html("{!! trans('messages.lang10') !!}");
                        $('#bodygenerico').html('<form method="POST" action="{{ route('edicion-de-usuario-id') }}" ><input type="hidden" name="_token" value="{{ csrf_token() }}" /><input type="hidden" name="id" value="'+response.id+'" /><div class="row mb-3"><label for="name" class="col-md-4 col-form-label text-md-end">{!! trans('messages.lang6') !!}</label><div class="col-md-6"><input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="'+response.nombre+'" required autocomplete="name" autofocus>@error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div><div class="row mb-3"><label for="email" class="col-md-4 col-form-label text-md-end">Email</label><div class="col-md-6"><input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="'+response.email+'" required autocomplete="email">@error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div><div class="row mb-0"><div class="col-md-6 offset-md-4"><button type="submit" class="btn btn-primary">{!! trans('messages.lang10') !!}</button></div></div></form>');
                        $('#footergenerico').html('<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarmodal()">{!! trans('messages.lang5') !!}</button>');
                        $('#modalgenerico').modal("show");
                    }
                    else
                    {
                        $('#titulogenerico').html("{!! trans('messages.lang10') !!}");
                        $('#bodygenerico').html("{!! trans('messages.lang11') !!}");
                        $('#footergenerico').html('<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarmodal()">{!! trans('messages.lang5') !!}</button>');
                        $('#modalgenerico').modal("show");
                    }
                    
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        function deleteus(id)
        {
            $('#modalgenerico').modal("show");
        }
        function history(id)
        {
            $('#modalgenerico').modal("show");
        }
        function addveh(id)
        {
            $('#modalgenerico').modal("show");
        }
        function cerrarmodal()
        {
            $('#modalgenerico').modal("hide");
        }
    </script>
@else

@endif

    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
               <!-- <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>-->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!--Comprobamos si el status esta a true y existe más de un lenguaje-->
                        @if (config('locale.status') && count(config('locale.languages')) > 1)
                            <div class="top-right links">
                                @foreach (array_keys(config('locale.languages')) as $lang)
                                    @if ($lang != App::getLocale())
                                        <a href="{!! route('lang.swap', $lang) !!}">
                                        {!! trans('messages.lang') !!}<small>&nbsp;{!! trans('messages.lang1') !!}</small>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
