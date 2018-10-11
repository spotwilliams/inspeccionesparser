@extends('layouts.app')

@section('content')
    @include('shared.errors')
    <form action="{{route('exportarFile')}}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="transactionId" value="{{$transactionId}}">
        <p class="help-block">Click para descargar el resultado</p>
        <button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Descargar</button>
        <a class="btn btn-default" href="{{route('index')}}"><i class="fa fa-back"></i> Volver</a>
    </form>

@endsection