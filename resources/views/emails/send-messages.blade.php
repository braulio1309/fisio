<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div class="img">
        <img width="150" src="{{ asset('assets/img/logo.png') }}" alt="">
    </div>

    <div>
        <strong>{{ $title }}</strong>
    </div>

    <p>Estimado(a): </p>

    <p>El Cliente <strong>{{ $name }}</strong>, mediante el presente contacto, le manifiesta lo siguiente: </p>

    <span><strong>Mensaje: </strong>{{ $msg }}</span>

    <p>El mismo queda atento a su respuesta, bien sea por este medio. Para ello se anexa lo sigueinte:</p>

    <p><strong>Correo Electrónico: </strong> {{ $email }} </p>

</body>
</html>
