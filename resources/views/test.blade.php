<form method="post" enctype="multipart/form-data" action="{{url('test')}}">
{!! csrf_field() !!}
    <label>&Aacute;rea: </label>

    <select name="area">

        <option value="APrA">APrA</option>
        <option value="EP">Espacio Público</option>
        <option value="Trabajo">Trabajo</option>
        <option value="GOCHU">GOCHU</option>

    </select>

    <label>Archivo: </label>
    <input type="file" name="archivo" accept=".xls, .xlsx, .csv">

    <br>
    <br>
    <input type="submit" value="Aceptar"/>
</form>