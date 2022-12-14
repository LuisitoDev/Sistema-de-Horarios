<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link rel="shortcut icon" href="../assets/Logo-FCFM.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/registroAlumnos.css')}}">
    <title>Registro Dispositivo Alumno</title>
</head>
<body class="main-background">
    <div class="row justify-content-center">
        <div class="col col-md-10 col-xl-7 p-4">
            <img src="{{asset('images/Logo-FCFM.png')}}" class="img-fluid logo-registro m-auto d-block mt-5"  alt="Logo De la facutldad de ciencias fisico matematicas" />
            <h5 class="text-center h2 my-2 display-3">Agregar Dispositivo</h5>
            <hr>    
            <p class='fw-light text-center p-3 ' style="border-radius: 16px; border: 1px solid #1e4ea1;">
                Hay una solicitud pendiente de ser aprobada para vincular este dispositivo al correo <b class="fw-bold">{{$previousDevice->correo_universitario}}</b> <br/>
                <span>Folio de la solicitud: <b class="fw-bold">{{$previousDevice->id_solicitud}}</b></span> <br/>
                Espera a que el administrador autorice la solicitud para poder cargar tus horas con este dispositivo.
            </p>
            <p class="text-center fw-bold mb-0">¿Eres otro asesor y no estas registrado? 
            </p>
            <div class="no-device w-100 text-center mt-0"> 
                <a href="{{route('assessors.register')}}">Haz click aqui</a> 
            </div>
        </div>
    </div>
</body>
</html>