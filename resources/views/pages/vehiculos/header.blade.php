<nav class="navbar navbar-expand navbar-black navbar-dark m-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('mostrarVehiculos')}}" class="nav-link {{ Route::is('mostrarVehiculos') ? 'active' : '' }}">Vehículos</a>
        </li>
        @can('formCrearVehiculo')
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('formCrearVehiculo')}}" class="nav-link {{ Route::is('formCrearVehiculo') ? 'active' : '' }}">Nuevo vehículo</a>
            </li>
        @endcan
    </ul>
</nav>