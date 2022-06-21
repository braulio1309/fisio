<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .button {
            background-color: ##8b5cf6; /* Green */
            border: none;
            color: #fff;
            padding: 13px 28px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
        }
        .text {
            color: #fff;
        }
    </style>

</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Estimado: <strong>{{ $user }}</strong></p>

    <p>A través de este correo electrónico se confirma su registro dentro de nuestra plataforma. Haga clic en el siguiente botón para validar el proceso.</p>

    <a href="{{ $url }}" class="button"><span class="text">Validar >Registro</span></a>

    <p>Gracias por confiar en  nosotros. <strong>¡Efecto Granel!</strong></p>
</body>
</html>
