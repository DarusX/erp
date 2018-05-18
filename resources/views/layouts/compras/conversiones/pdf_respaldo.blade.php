@extends('formato_general_pdf')

@section('head')
    <style>
        #container {
            margin: auto;
            width: 60%;
            padding: 10px;
            text-align: center;
            font-size: 18px;
        }
    </style>
@stop

@section('content')
    <div id="container">
        <h2>Detalles de la conversión</h2>
        <table align="center" border="0">
            <tr>
                <th>Folio:</th>
                <td>{{$conversion->id_conversion}}</td>
            </tr>
            <tr>
                <th>Fecha:</th>
                <td>{{Date::parse($conversion->fecha)->format('d \d\e F \d\e Y \a \l\a\s H:m')}}</td>
            </tr>
            <tr>
                <th>Código origen:</th>
                <td>{{$conversion->detalles->productoOrigen->codigo_producto}}</td>
            </tr>
            <tr>
                <th>Cantidad origen:</th>
                <td>{{number_format($conversion->detalles->cantidad_origen, 2)}}</td>
            </tr>
            <tr>
                <th>Código destino:</th>
                <td>{{$conversion->detalles->productoDestino->codigo_producto}}</td>
            </tr>
            <tr>
                <th>Cantidad destino:</th>
                <td>{{number_format($conversion->detalles->cantidad_destino, 2)}}</td>
            </tr>
            <tr>
                <th>Sucursal:</th>
                <td>{{$conversion->sucursal->nombre}}</td>
            </tr>
            <tr>
                <th>Almacén origen:</th>
                <td>{{$conversion->detalles->almacenOrigen->almacen}}</td>
            </tr>
            <tr>
                <th>Almacén destino:</th>
                <td>{{$conversion->detalles->almacenDestino->almacen}}</td>
            </tr>
        </table>
        <hr/>
        <br/><br/><br/>
        <span>______________________________</span><br/>
        {{$conversion->usuario->nombre}}
    </div>
@stop