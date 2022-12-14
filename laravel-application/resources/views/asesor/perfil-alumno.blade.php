<!-- DEPRECATED VIEW -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    {{-- <link rel="shortcut icon" href="{{asset('assets/Logo-FCFM.ico')}}" type="image/x-icon"> --}}
    <link rel="shortcut icon" href="../assets/Logo-FCFM.ico" type="image/x-icon">
    <script src="{{asset('js/app.js')}}" defer></script>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/perfilAlumno.css')}}">
    <title>Perfil Alumno</title>
</head>

<body class="main-background">
    <div class="container-fluid min-vh-100">
        @include('header')

        <main id="Perfil" class="pt-lg-4">
        </main>

        <nav class="box-blue__background fixed-bottom text-white d-block d-lg-none">
            <div class="row">
                <div class="col text-center">
                    <button class="btn text-white fs-1 w-100 shadow-none">
                        <i class="fa-solid fa-user"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <button class="btn text-white fs-1 w-100 shadow-none">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <button class="btn text-white fs-1 w-100 shadow-none">
                        <i class="fa-solid fa-chart-column"></i>
                    </button>
                </div>
            </div>
        </nav>
    </div>
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
