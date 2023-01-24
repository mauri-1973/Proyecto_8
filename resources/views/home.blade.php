@extends('layouts.app')

@section('content')
@if(session()->has('type') && session('type') == "admin")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session()->has('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if ($errors->any())@foreach ($errors->all() as $error)<div><small class="text-danger">{{$error}}</small></div>@endforeach @endif
            <div class="card">
                <div class="card-header">{!! trans('messages.lang2') !!}</div>
                <div class="card-body">
                    <button onclick="addus()" type="button" class="btn btn-primary mb-4">{!! trans('messages.lang4') !!}</button>
                    <table class="table table-bordered data-table nowrap" id="data-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th style="width:19%">Name</th>
                                <th style="width:25%">Email</th>
                                <th style="width:28%">Acciones Usuario</th>
                                <th style="width:28%">Acciones Vehículos</th>
                            </tr>

                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{!! trans('messages.lang2') !!}</div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- The Modal -->
<div class="modal" id="modalgenerico">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="titulogenerico">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal" onclick="cerrarmodal()">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="bodygenerico">
                Modal body..
            </div>

            <!-- Modal footer -->
            <div class="modal-footer" id="footergenerico">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarmodal()">Close</button>
            </div>

        </div>
    </div>
</div>
@endsection

