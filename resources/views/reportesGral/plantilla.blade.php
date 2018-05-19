@extends('layouts.master')
@section('contenido')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Plantilla de empleados</div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-hover" id="tablaResultados">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Sucursal</th>
                                <th>Puesto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $empleado)
                                <tr>
                                    <td>{{$empleado->nombre}}</td>
                                    <td>{{$empleado->sucursal}}</td>
                                    <td>{{$empleado->puesto}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection