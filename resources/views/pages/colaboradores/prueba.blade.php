@extends('themes.lte.layout')

@section('titulo')
    GLPI
@endsection

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('assets/lte/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>

        $(function() {

            //Permite que a los select de selección de identificación, EPS y ARL se les asigne una barra de búsqueda haciendolos más dinámicos
            function activarSelect2Colaborador() {
                $('#selectCodigo').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Activo',
                    language: {
                        noResults: function() {
                            return 'No hay resultado';
                        }
                    }
                });
                $('.select2eps').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Seleccione EPS',
                    language: {
                        noResults: function() {
                            return 'No hay resultado';
                        }
                    }
                });
                $('.select2arl').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Seleccione ARL',
                    language: {
                        noResults: function() {
                            return 'No hay resultado';
                        }
                    }
                });
            }

            activarSelect2Colaborador();

            //Función que permite traer los datos del propietario del código del activo seleccionado y una vez traidos, se colocan automáticamente en su respectivo input
            $('#selectCodigo').change(function() {
                $.ajax({
                    url: '/colaboradores/persona',
                    type: 'GET',
                    data: {
                        colaborador: $('#selectCodigo option:selected').val(),
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#inputCodigo').val($('#selectCodigo option:selected').text());
                        $('#inputNombre').val(response['firstname']);
                        $('#inputApellido').val(response['realname']);
                        $('#inputIdentificacion').val(response['registration_number']);
                        $('#inputEmail').val(response['email']);

                        if (response['phone2'].includes('Aviomar')) {
                            $('#selectEmpresa').val(1);
                        } else if (response['phone2'].includes('Snider')) {
                            $('#selectEmpresa').val(2);
                        } else if (response['phone2'].includes('Colvan')) {
                            $('#selectEmpresa').val(3);
                        }

                        $('.colaborador').each(function(index) {
                            if ((!$(this).val() == '') && ($(this).hasClass(
                                    'is-invalid'))) {
                                $(this).removeClass("is-invalid");
                            }
                        });
                    },
                    error: function() {
                        console.log('Error obteniendo los datos de GLPI');
                    }
                });
            });

            //Botón que limpia la información del formulario de colaborador 
            $('#botonLimpiar').click(function() {
                $('.colaborador').each(function(index) {
                    $(this).val('');
                    if ($(this).hasClass('is-invalid')) {
                        $(this).removeClass("is-invalid");
                    }
                });
                activarSelect2Colaborador();
            });

        });

    </script>

@endsection

@section('contenido')

    <div class="content mb-n2">
        @include('pages.colaboradores.header')
    </div>

    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
                
                {{-- <div class="card card-primary mb-n1">
                    <div class="card-header ">
                        <h3 class="card-title">Prueba GLPI buscar por usuario</h3>
                        <div class="card-tools">
                            <button id="botonComprimirColaborador" type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">

                                <select name="colaborador" id="prueba" class="form-control" style="width: 100%;" required>
                                    <option selected="selected" value="" disabled></option>
                                    @foreach ($colaboradores as $colaborador)
                                        <option value="{{ $colaborador['id'] }}">C.C. {{ $colaborador['registration_number'] }} - {{ $colaborador['firstname'] }} {{ $colaborador['realname'] }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-6">
                                <input type="text" id="inputCodigo" class="form-control" placeholder="Activo">
                            </div>
                        </div>
                    </div>
                </div> --}}


                <form id="" action="{{ route('crearColaborador') }}" method="POST" novalidate>
                    @csrf
                    <input id="casoIngreso" type="hidden" name="casoIngreso" value="{{ old('casoIngreso') }}">
                    
                    <div class="card card-primary mb-n1">
                        <div class="card-header pb-1">
                            <h3 class="card-title">Crear nuevo colaborador</h3>
                            <div class="card-tools">
                                <button id="botonComprimirColaborador" type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                    
                        <div class="card-body mb-n4">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="hidden" id="inputCodigo" name="codigo" value="{{ old('codigo') }}" required>
                                        <label for="selectCodigo">Ingrese el activo</label>
                                        <select name="selectCodigo" id="selectCodigo" class="colaborador form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" style="width: 100%;" required>
                                            <option selected="selected" value="" disabled></option>
                                            @foreach ($computadores as $computador)
                                                <option value="{{ $computador['users_id'] }}"
                                                {{ $computador['users_id'] == old('selectCodigo') ? 'selected' : '' }}>{{ $computador['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('codigo')) 
                                            <div class="invalid-feedback">
                                                {{ $errors->first('codigo') }}
                                            </div>            
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputNombre">Ingrese el nombre</label>
                                        <input type="text" class="colaborador form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" id="inputNombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" autocomplete="off" required>
                                            @if ($errors->has('nombre')) 
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('nombre') }}
                                                </div>            
                                            @endif
                                    </div>
                                </div> 
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputApellido">Ingrese el apellido</label>
                                        <input type="text" class="colaborador form-control {{ $errors->has('apellido') ? 'is-invalid' : '' }}" id="inputApellido" name="apellido" value="{{ old('apellido') }}" placeholder="Apellido" autocomplete="off" required>
                                            @if ($errors->has('apellido')) 
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('apellido') }}
                                                </div>            
                                            @endif
                                    </div>
                                </div>  
                            </div>
                            <div class="row">          
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputIdentificacion">Ingrese la identificación</label>
                                        <input type="text" class="colaborador form-control {{ $errors->has('identificacion') ? 'is-invalid' : '' }}" id="inputIdentificacion" name="identificacion" value="{{ old('identificacion') }}" placeholder="Identificación" autocomplete="off" required>
                                            @if ($errors->has('identificacion')) 
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('identificacion') }}
                                                </div>          
                                            @endif  
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputEmail">Ingrese el correo empresarial</label>
                                        <input type="text" class="colaborador form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="inputEmail" name="email" value="{{ old('email') }}" placeholder="Correo empresarial" autocomplete="off">
                                            @if ($errors->has('email')) 
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>          
                                            @endif  
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputTelefono">Ingrese el teléfono</label>
                                        <input type="tel" class="colaborador form-control {{ $errors->has('tel_contacto') ? 'is-invalid' : '' }}" id="inputTelefono" name="tel_contacto" value="{{ old('tel_contacto') }}" placeholder="Teléfono" autocomplete="off" required>
                                            @if ($errors->has('tel_contacto')) 
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('tel_contacto') }}
                                                </div>          
                                            @endif  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="selectEps">Ingrese la EPS</label>
                                        <select id="selectEps" class="select2eps colaborador form-control {{ $errors->has('id_eps') ? 'is-invalid' : '' }}" style="width: 100%;" name="id_eps" required> 
                                            <option selected="selected" value="" disabled></option>
                                            @foreach ($eps as $ep)
                                                <option value="{{ $ep->id_eps }}"
                                                    {{ $ep->id_eps == old('id_eps') ? 'selected' : '' }}>{{ $ep->eps }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('id_eps')) 
                                            <div class="invalid-feedback">
                                                {{ $errors->first('id_eps') }}
                                            </div>            
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="selectArl">Ingrese el ARL</label>
                                        <select id="selectArl" class="select2arl colaborador form-control {{ $errors->has('id_arl') ? 'is-invalid' : '' }}" style="width: 100%;" name="id_arl" required>
                                            <option selected="selected" value="" disabled></option>
                                            @foreach ($arl as $ar)
                                                <option value="{{ $ar->id_arl }}"
                                                    {{ $ar->id_arl == old('id_arl') ? 'selected' : '' }}>{{ $ar->arl }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('id_arl')) 
                                            <div class="invalid-feedback">
                                                {{ $errors->first('id_arl') }}
                                            </div>            
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="selectEmpresa">Ingrese la empresa a la que pertenece</label>
                                        <select id="selectEmpresa" class="colaborador form-control {{ $errors->has('id_empresa') ? 'is-invalid' : '' }}" style="width: 100%;" name="id_empresa"
                                            required>
                                            <option selected="selected" value="" disabled>Seleccione la empresa</option>
                                            @foreach ($empresas as $empresa)
                                                <option value="{{ $empresa->id_empresas}}"
                                                    {{ $empresa->id_empresas == old('id_empresa') ? 'selected' : '' }}>{{ $empresa->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('id_empresa')) 
                                            <div class="invalid-feedback">
                                                {{ $errors->first('id_empresa') }}
                                            </div>            
                                        @endif
                                    </div>
                                </div>
                            </div>                           
                            <div class="row">

                                {{-- <div class="col-sm-4">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix pt-4">
                                        <div class="icheck-primary d-inline">
                                            <label for="checkVehiculo">
                                                ¿El colaborador ingresa vehículo?
                                            </label>
                                            <input type="checkbox" id="checkVehiculo">
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="inputDescripcion">Ingrese una descripción</label>
                                        <textarea id="inputDescripcion" class="colaborador form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" name="descripcion">
                                            {{ old('descripcion') }}
                                        </textarea>
                                        @if ($errors->has('descripcion')) 
                                            <div class="invalid-feedback">
                                                {{ $errors->first('descripcion') }}
                                            </div>          
                                        @endif 
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button id="botonCrear" type='submit' class="btn btn-primary">Crear colaborador</button>
                            <button id="botonLimpiar" type='button' class="btn btn-secondary">Limpiar</button>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                </form>

            </div>
        </div>
    </section>

@endsection