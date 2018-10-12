@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subida de archivos</div>

                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
