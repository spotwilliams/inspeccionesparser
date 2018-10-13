@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @include('shared.errors')
                        <form method="post" enctype="multipart/form-data" action="{{route('subirarchivo')}}">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label class="label label-info">Seleccione uno o m&aacute;s archivos </label>
                            </div>
                            <div class="form-group">
                                <input type="file" name="archivo[]" accept=".xls, .xlsx, .csv" multiple>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary pull-right">Aceptar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
