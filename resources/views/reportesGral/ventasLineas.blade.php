@extends('layouts.master') 
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
@endsection
@section('contenido')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Ventas por línea</div>
            </div>
            <div class="portlet-body form" style="display: block;">
                <form action="" method="post" id="formVentasLineas">
                    {{csrf_field()}}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Líneas</label>
                                    <select class="form-control" name="id_linea">
                                        @foreach($lineas as $linea)
                                        <option value="{{$linea->id_linea}}">{{$linea->linea}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Año</label>
                                    <select name="year" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Mes</label>
                                    <select name="month" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="form-actions">
                    <button type="button" class="btn green" onclick="buscarVentasLineas()">Buscar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Reporte</div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-hover" id="tablaResultados">
                        <thead>
                            <tr>
                                <th>Sucursal</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th>vendedor</th>
                            </tr>
                        </thead>
                        <tbody id="datos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@section('javascript')
<script>
    var months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    $.each(months, function(index, value){
        $("select[name='month']").append($("<option></option>").attr({
            value: index+1
        }).append(value))
    })
    for (let index = 2011; index <= new Date().getFullYear(); index++) {
        $("select[name='year']").append($("<option></option>").attr({
            value: index
        }).append(index))
    }
    function buscarVentasLineas() {
        $("#datos").empty()
        $.ajax({
            url: "/reportes/ventas/lineas/buscar",
            method: "POST",
            data: $("#formVentasLineas").serialize(),
            success: function (data) {
                $.each(data, function (index, value) {
                    var cols = [];
                    cols.push($("<td></td>").append(value.sucursal))
                    cols.push($("<td></td>").append(value.codigo_producto))
                    cols.push($("<td></td>").append(value.descripcion))
                    cols.push($("<td></td>").append(value.cantidad))
                    cols.push($("<td></td>").append(value.monto))
                    cols.push($("<td></td>").append(value.vendedor))
                    $("#datos").append($("<tr></tr>").append(cols));
                })
            }
        })
    }
</script>
@endsection