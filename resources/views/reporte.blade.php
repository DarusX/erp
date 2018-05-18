<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $p)
                    <tr>
                        <td>{{$p->id_producto}}</td>
                        <td>{{$p->codigo_producto}}</td>
                        <td>{{$p->descripcion}}</td>
                        <td>{{$p->familia}}</td>
                        <td>{{$p->linea}}</td>
                        <td>{{$p->factor_conversion}}</td>
                        <td>{{$p->dias_entrega_promedio}}</td>
                        <td>{{$p->cantidad}}</td>
                        <td>{{$p->monto_venta}}</td>
                        <td>{{$p->precio}}</td>
                        <td>{{$p->monto_costo}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $productos->render() !!}
        </div>
    </div>
</body>

</html>