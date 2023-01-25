<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! trans('messages.lang26') !!}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>{!! trans('messages.lang24') !!} {{$user->name}}. Email: {{$user->email}}</h2>
            </div>
            <div class="col-md-4">
                <div class="mb-4 d-flex justify-content-end">
                    <a class="btn btn-primary" href="{{ URL::to('#') }}">{!! trans('messages.lang26') !!}</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <caption>{!! trans('messages.lang25') !!}</caption>
                    <thead>
                      <tr>
                        <th scope="col">{!! trans('messages.lang16') !!}</th>
                        <th scope="col">{!! trans('messages.lang17') !!}</th>
                        <th scope="col">{!! trans('messages.lang18') !!}</th>
                        <th scope="col">{!! trans('messages.lang19') !!}</th>
                        <th scope="col">{!! trans('messages.lang20') !!}</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($veh as $producto)
                        <tr>
                            <th scope="row">{{ $producto->marca }}</th>
                            <td>{{ $producto->modelo }}</td>
                            <td>{{ $producto->patente }}</td>
                            <td>{{ $producto->annio }}</td>
                            <td>{{ $producto->precio }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>