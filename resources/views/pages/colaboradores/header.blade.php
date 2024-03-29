<nav class="navbar navbar-expand navbar-black navbar-dark m-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('mostrarColaboradores')}}" class="nav-link {{ Route::is('mostrarColaboradores') ? 'active' : '' }}">Colaboradores con activo</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('mostrarColaboradores2')}}" class="nav-link {{ Route::is('mostrarColaboradores2') ? 'active' : '' }}">Colaboradores sin activo</a>
        </li>
        @can('formCrearColaborador')
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('formCrearColaborador')}}" class="nav-link {{ Route::is('formCrearColaborador') ? 'active' : '' }}">Nuevo colaborador</a>
            </li> 
        @endcan
    </ul>
</nav>