<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Solicitud de vacaciones</h1>
    <p>El empleado  <strong>{{ $data['name'] }}</strong> con correo  <strong>{{ $data['email'] }}</strong> ha solicitado el siguente día de vacaciones </p>
    <strong>{{ $data['day'] }}</strong>
 </body>
</html>
