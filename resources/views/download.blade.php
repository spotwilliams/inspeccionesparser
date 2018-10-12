
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Descarga de archivos</div>

                    <div class="card-body">
                        @include('shared.errors')
                        <form action="{{route('exportarFile')}}" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="transactionId" value="{{$transactionId}}">
                            <p class="help-block">Click para descargar el resultado</p>
                            <button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Descargar</button>
                            <a class="btn btn-default" href="{{route('index')}}"><i class="fa fa-back"></i> Volver</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
