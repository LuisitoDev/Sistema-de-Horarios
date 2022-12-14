<!-- DEPRECATED VIEW -->
<header class="row position-fixed w-100" style="margin-bottom: 150px; z-index: 10;" >
    <nav class="navbar navbar-expand-lg button-color col-lg-12 d-none d-lg-block">
       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon  text-white "></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <span class="nav-link active text-white fw-bold me-4 " aria-current="page" href="#">Registro FCFM</span>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-white " aria-current="page" href="#">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="#">Solicitudes</a>
            </li>
            <li class="nav-item">
                <form action="{{route('admin.logout')}}">
                    <button class="nav-link text-white">Cerrar sesión</button>
                </form>
                {{-- <a class="nav-link text-white " href="#">Cerrar sesión</a> --}}
            </li>
            </ul>
            <span class="navbar-text text-white ">
            Hola, Derek!
            </span>
        </div>
    </nav>
</header>