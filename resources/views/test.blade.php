@extends('layouts.app')

@section('content')
    @include('shared.errors')
    <form method="post" enctype="multipart/form-data"  action="{{route('subirarchivo')}}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="area">&Aacute;rea: </label>
            <select name="area" id="area" class="form-control">
                <option value="APrA">APrA</option>
                <option value="EP">Espacio Público</option>
                <option value="Trabajo">Trabajo</option>
                <option value="GOCHU">GOCHU</option>

            </select>
        </div>
        <div class="form-group">
            <label>Archivo: </label>
            <input type="file" name="archivo[]" accept=".xls, .xlsx, .csv" multiple>

        </div>
        <div class="form-group">

            <br>
            <br>
            <input type="submit" value="Aceptar"/>
        </div>
    </form>
@endsection