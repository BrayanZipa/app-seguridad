@extends('themes.lte.layout')

@section('titulo')
    Registros
@endsection

@section('css')
    <!-- Token de Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('assets/lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- JavaScript propio -->
    {{-- <script src="{{ asset('js/registros/registrosCrear.js') }}"></script> --}}

    <script>
        $(function () {

            //Token de Laravel
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            
            //Permite que a los select de selección de EPS y ARL de todos los formularios se les asigne una barra de búsqueda haciendolos más dinámicos, también se le asigna select de persona
            function activarSelect2Registros() {
                $('#selectPersona').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Seleccione a la persona',
                    language: {
                        noResults: function () {
                            return 'No hay resultado';
                        }
                    }
                });
                $('.select2EPS').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Seleccione EPS',
                    language: {
                        noResults: function () {
                            return 'No hay resultado';
                        }
                    }
                });
                $('.select2ARL').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Seleccione ARL',
                    language: {
                        noResults: function () {
                            return 'No hay resultado';
                        }
                    }
                });
            }
            activarSelect2Registros();

            //Función que permite que por medio de una solicitud Ajax se listen en un select todas las personas que pertenezcan a un tipo de persona que se haya seleccionado
            function listarPersonas() {
                $('#selectPersona').empty();   
                $('#selectPersona').append("<option value=''>Seleccione a la persona</option>");
                $.ajax({
                    url: '/registros/personas',
                    type: 'GET',
                    data: {
                        tipoPersona: $('#selectTipoPersona option:selected').val(),
                    },
                    dataType: 'json',
                    success: function(response){
                        $('#buscarPersona').css('display', 'block'); 
                        $.each(response.data, function(key, value){                   
                            $('#selectPersona').append("<option value='" + value.id_personas + "'> C.C. " + value.identificacion + " - " + value.nombre + " " + value.apellido + "</option>");
                        });                                              
                    }, 
                    error: function(){
                        console.log('Error obteniendo los datos de las personas');
                    }
                }); 
            }

            //Función que se activa cuando el usuario selecciona alguna opción del select tipo de persona
            $('#selectTipoPersona').change(function() { 
                listarPersonas();
            });
            
            //Función que permite que al seleccionar a una persona se traiga su información por medio de una solicitud Ajax y dependiendo del tipo de persona esta información se muestre en los diferentes formularios
            $('#selectPersona').change(function() { 
                if($('.registros').hasClass('is-invalid')){
                    $('.registros').removeClass('is-invalid');
                }  
                $.ajax({
                    url: '/registros/persona',
                    type: 'GET',
                    data: {
                        persona: $('#selectPersona option:selected').val(),
                    },
                    dataType: 'json',
                    success: function(response){
                        $('#idPersona').val(response.id_personas);
                        if($('#formVisitanteConductor').is(':visible')){
                            $('#formVisitanteConductor').css('display', 'none');
                        } else if ($('#formColaboradorSinActivo').is(':visible')){
                            $('#formColaboradorSinActivo').css('display', 'none');
                        }else if ($('#formColaboradorConActivo').is(':visible')){
                            $('#formColaboradorConActivo').css('display', 'none');
                        }

                        if(response.id_tipo_persona == 1 || response.id_tipo_persona == 4){
                            $('#formRegistros1').attr('action','/registros/editar_persona/' + response.id_personas); 
                            $('#inputId').val(response.id_personas);
                            $('#inputFoto').val(response.foto);
                            $('#fotografia').attr('src', response.foto);  
                            $('#inputNombre').val(response.nombre);
                            $('#inputApellido').val(response.apellido);
                            $('#inputIdentificacion').val(response.identificacion);
                            $('#inputTelefono').val(response.tel_contacto);
                            $('#selectEps').val(response.id_eps);
                            $('#selectArl').val(response.id_arl);
                            $('#inputColaborador').val('');
                            $('#selectEmpresa').val('');
                            $('#inputDescripcion').val('');

                            if(response.id_tipo_persona == 1){ 
                                if($('#checkActivo').prop('checked')){
                                    $('#checkActivo').trigger('click');
                                }
                                if($('#checkVehiculo').prop('checked')){
                                    $('#checkVehiculo').trigger('click');
                                } else {
                                    $('#divVehiculo').css('display', 'none');
                                    $('#selectVehiculo').prop('required', false);
                                }

                                $('#inputActivo').val(response.activo);
                                $('#inputCodigo').val(response.codigo); 
                                $('#selectEps').prop('required', false);
                                $('#selectArl').prop('required', false);
                                $('#titulo').text('Información visitante');
                                $('#checkBox').css('display', ''); 
                                $('#formVisitanteConductor').css('display', 'block'); 
                            } else {
                                obtenerVehiculos('#selectVehiculo');
                                $('#selectVehiculo').prop('required', true);
                                $('#divVehiculo').css('display', 'block');
                                $('#selectEps').prop('required', true);
                                $('#selectArl').prop('required', true);
                                $('#titulo').text('Información conductor');
                                $('.visitante').css('display', 'none');   
                                $('#formVisitanteConductor').css('display', 'block'); 
                            }

                        }  else if(response.id_tipo_persona == 2){
                            if($('#checkVehiculo2').prop('checked')){
                                $('#checkVehiculo2').trigger('click');
                            } 
                            $('#formRegistros2').attr('action','/registros/editar_persona/' + response.id_personas);
                            $('#inputId2').val(response.id_personas);
                            $('#inputNombre2').val(response.nombre);
                            $('#inputApellido2').val(response.apellido);
                            $('#inputIdentificacion2').val(response.identificacion);
                            $('#inputEmail2').val(response.email);
                            $('#inputTelefono2').val(response.tel_contacto);
                            $('#selectEps2').val(response.id_eps);
                            $('#selectArl2').val(response.id_arl);
                            $('#selectEmpresa2').val(response.id_empresa);
                            $('#formColaboradorSinActivo').css('display', 'block'); 
                            
                        }  else if(response.id_tipo_persona == 3){
                            if($('#checkVehiculo3').prop('checked')){
                                $('#checkVehiculo3').trigger('click');
                            }
                            $('#formRegistros3 .registros').each(function(index) {
                                $(this).val('');
                            });
                            obtenerColaborador(response);
                            $('#formRegistros3').attr('action','/registros/editar_persona/' + response.id_personas);
                            $('#inputId3').val(response.id_personas);
                            $('#inputTelefono3').val(response.tel_contacto);
                            $('#selectEps3').val(response.id_eps);
                            $('#selectArl3').val(response.id_arl);
                            $('#formColaboradorConActivo').css('display', 'block'); 
                        }   
                        activarSelect2Registros();              
                    }, 
                    error: function(){
                        console.log('Error obteniendo los datos de la persona');
                    }
                }); 
            }); 

            //Función que permite que al seleccionar una persona de tipo colaborador con activo se traiga su información directamente desde el API de GLPI por medio de una solicitud Ajax
            function obtenerColaborador(data){      
                if($('#mensajeError').length){ $('#mensajeError').remove(); }  
                if($('#mensajeCodigo').length){ $('#mensajeCodigo').remove(); } 
                
                $.ajax({
                    url: '/colaboradores/colaboradoridentificado',
                    type: 'GET',
                    data: {
                        colaborador: data.identificacion
                    },
                    dataType: 'json',
                    success: function(response) {
                        if ('error' in response) {  
                            $('#inputCodigo3').addClass('is-invalid');                
                            $('#inputCodigo3').val('*El colaborador no esta registrado en el sistema GLPI');
                            $('#inputNombre3').val(data.nombre);
                            $('#inputApellido3').val(data.apellido);
                            $('#inputIdentificacion3').val(data.identificacion);
                            $('#inputEmail3').val(data.email);
                            $('#selectEmpresa3').val(data.id_empresa);

                        } else {                  
                            $.ajax({
                                url: '/colaboradores/computador',
                                type: 'GET',
                                data: {
                                    colaborador: response.id,
                                },
                                dataType: 'json',
                                success: function(activo) {
                                    if ('error' in activo) {
                                        $('#inputCodigo3').addClass('is-invalid');
                                        $('#inputCodigo3').val('*Colaborador registrado en GLPI pero sin activo asignado');
                                    } else {
                                        $('#inputCodigo3').val(activo['name']); 
                                        if(data.codigo != activo['name']){
                                            $('#inputCodigo3').addClass('is-invalid');
                                            if($('#mensajeCodigo').length){ 
                                                $('#mensajeCodigo').text('El colaborador tiene asignado un nuevo activo, debe actualizar');
                                            } else {
                                                $('#inputCodigo3').after($('<div id="mensajeError" class="invalid-feedback">El colaborador tiene asignado un nuevo activo, debe actualizar</div>'));
                                            }     
                                        }
                                    }  
                                },
                                error: function() {
                                    console.log('Error obteniendo los datos de GLPI');
                                }
                            });

                            $('#inputIdentificacion3').val(response['registration_number']);
                            $('#inputNombre3').val(response['firstname']);
                            $('#inputApellido3').val(response['realname']);
                            $('#inputEmail3').val(response['email']);

                            if (response['phone2'].includes('Aviomar')) {
                                $('#selectEmpresa3').val(1);
                            } else if (response['phone2'].includes('Snider')) {
                                $('#selectEmpresa3').val(2);
                            } else if (response['phone2'].includes('Colvan')) {
                                $('#selectEmpresa3').val(3);
                            }       
                        }         
                    },
                    error: function() {
                        console.log('Error obteniendo los datos de GLPI');
                    }
                });
            }

            //Manejo de los checkbox al ser seleccionados y control de la vista de formularios   
            $('#checkVehiculo').on('change', function () {
                if ($('#checkVehiculo').is(':checked')) {
                    obtenerVehiculos('#selectVehiculo');
                    $('#selectVehiculo').prop('required', true);
                    $('#divVehiculo').css('display', 'block');
                } else {
                    if($('#selectVehiculo').hasClass('is-invalid')){
                        $('#selectVehiculo').removeClass('is-invalid');
                    }  
                    $('#selectVehiculo').prop('required', false);
                    $('#divVehiculo').css('display', 'none');
                } 
            });

            $('#checkVehiculo2').on('change', function () {
                if ($('#checkVehiculo2').is(':checked')) {
                    obtenerVehiculos('#selectVehiculo2');
                    $('#selectVehiculo2').prop('required', true);
                    $('#divVehiculo2').css('display', 'block');
                } else {
                    if($('#selectVehiculo2').hasClass('is-invalid')){
                        $('#selectVehiculo2').removeClass('is-invalid');
                    }  
                    $('#selectVehiculo2').prop('required', false);
                    $('#divVehiculo2').css('display', 'none');
                } 
            });

            $('#checkVehiculo3').on('change', function () {
                if ($('#checkVehiculo3').is(':checked')) {
                    obtenerVehiculos('#selectVehiculo3');
                    $('#selectVehiculo3').prop('required', true);
                    $('#divVehiculo3').css('display', '');
                } else {
                    if($('#selectVehiculo3').hasClass('is-invalid')){
                        $('#selectVehiculo3').removeClass('is-invalid');
                    }  
                    $('#selectVehiculo3').prop('required', false);
                    $('#divVehiculo3').css('display', 'none');
                } 
            });

            function name(checkbox, select, contentOculto) {
                if ($('#checkVehiculo3').is(':checked')) {
                    obtenerVehiculos('#selectVehiculo3');
                    $('#selectVehiculo3').prop('required', true);
                    $('#divVehiculo3').css('display', '');
                } else {
                    if($('#selectVehiculo3').hasClass('is-invalid')){
                        $('#selectVehiculo3').removeClass('is-invalid');
                    }  
                    $('#selectVehiculo3').prop('required', false);
                    $('#divVehiculo3').css('display', 'none');
                }  
            }

            $('#checkActivo').on('change', function () {
                if ($('#checkActivo').is(':checked')) {
                    $('#inputActivo').prop('required', true);
                    $('#inputCodigo').prop('required', true);
                    if($('#inputActivo').val().length <= 0){
                        $('#inputActivo').val('Computador');
                    }
                    $('#divActivo').css('display', '');         
                } else {
                    if($('#inputActivo, #inputCodigo').hasClass('is-invalid')){
                        $('#inputActivo, #inputCodigo').removeClass('is-invalid');
                    } 
                    $('#inputActivo').prop('required', false);
                    $('#inputCodigo').prop('required', false);
                    $('#divActivo').css('display', 'none');
                    $('#inputActivo').val('');
                    $('#inputCodigo').val('');
                }
            });

            //Función que permite que al seleccionar la opción de ingreso de vehículo en cualquiera de los formularios se haga una petención Ajax para consultar la información de los vehículos que esten asociados a la persona que este previamente seleccionada y estos se listen en un select
            function obtenerVehiculos(select) {
                $(select).empty();   
                $(select).append("<option selected='selected' value='' disabled>Seleccione el vehículo</option>");

                $.ajax({
                    url: '/registros/vehiculos/',
                    type: 'GET',
                    data: {
                        persona: $('#idPersona').val()
                    },
                    dataType: 'json',
                    success: function(response){
                        $.each(response, function(key, value){     
                            if(value.marca == null){
                                $(select).append("<option value='" + value.id_vehiculos + "'>" + value.tipo + " - " + value.identificador + "</option>");
                            }  else {
                                $(select).append("<option value='" + value.id_vehiculos + "'>" + value.tipo + " " + value.marca + " - " + value.identificador + "</option>");
                            }            
                        });   
                    }, 
                    error: function(errores){
                        console.log(errores);
                    }
                }); 
            }

            // Funciones anónimas que generan mensajes de error cuando el usuario intenta enviar algún formulario del módulo registros sin los datos requeridos, es una primera validación del lado del cliente
            (function () {
                'use strict'
                var form = document.getElementById('formRegistros1');
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        validacion(form);
                    }
                }, false);
            })();

            (function () {
                'use strict'
                var form = document.getElementById('formRegistros2');
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        validacion(form);
                    }
                }, false);
            })();

            (function () {
                'use strict'
                var form = document.getElementById('formRegistros3');
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        validacion(form);
                    }
                }, false);
            })();

            function validacion(form) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.registros').each(function (index) {
                        if (!this.checkValidity()) {
                            $(this).addClass('is-invalid');
                        }
                    });
                }
            }

            //Si en un input del cualquier formulario del módulo visitantes esta la clase is-invalid al escribir en el mismo input se elimina esta clase 
            $('input.registros').keydown(function (event) {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                }
            });

            //Si en un select del cualquier formulario del módulo visitantes esta la clase is-invalid al seleccionar algo en el mismo select se elimina esta clase 
            $('select.registros').change(function () {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                };
            });


            // $( "#formRegistros1" ).submit(function( event ) {
            //     alert( "Handler for .submit() called." );
            //     event.preventDefault();
            // });

            // function guardar() {
            //     datos = {};
            //     datos['foto'] = $('#inputFoto').val();
            //     datos['nombre'] = $('#inputNombre').val();
            //     datos['apellido'] = $('#inputApellido').val();
            //     datos['identificacion'] = $('#inputIdentificacion').val();
            //     datos['tel_contacto'] = $('#inputTelefono').val();
            //     datos['id_eps'] =  $('#selectEps').val();
            //     datos['id_arl'] = $('#selectArl').val();

            //     datos['activo'] =  $('#inputActivo').val();
            //     datos['codigo'] = $('#inputCodigo').val();
                
            //     console.log(datos);

            //     $.ajax({
            //         url: '/visitantes/editar/' + $('#idPersona').val(),
            //         type: 'PUT',
            //         data: datos,
            //         // dataType: 'json',
            //         success: function(response){
            //             console.log(response);
            //         }, 
            //         error: function(errores){
            //             console.log(errores);
            //         }
            //     }); 
            // }


        });        
    </script>

@endsection

@section('contenido')
    <div class="content mb-n2">
        @include('pages.registros.header')
    </div>

    <section class="content-header mb-n4">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Crear nuevo registro</h3>
                        <div class="card-tools">
                            <button id="botonComprimirVisitante" type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body mb-n4 mt-n1" >
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="selectTipoPersona">Seleccione el tipo de persona</label>
                                        <select id="selectTipoPersona" class="select2bs4 form-control" style="width: 100%;">
                                            <option selected="selected" value="" disabled>Tipo de persona</option>
                                            @foreach ($tipoPersonas as $tipo)
                                                <option value="{{ $tipo->id_tipo_personas }}" {{ $tipo->id_tipo_personas == old('tipoPersona') ? 'selected' : '' }}>{{ $tipo->tipo }}</option>
                                            @endforeach
                                        </select>  
                                </div>
                            </div>
                            <div id="buscarPersona" class="col-sm-6" style="display: none">
                                <input id="idPersona" type="hidden" value="">
                                <div class="form-group">
                                    <label for="selectPersona">Seleccione a la persona</label>
                                    <select id="selectPersona" class="select2bs4 form-control" style="width: 100%;" name="id_persona">
                                        <option selected="selected" value="" disabled></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="formVisitanteConductor" style="display: none">
                    @include('pages.registros.formularioVisitanteConductor')
                </div>

                <div id="formColaboradorSinActivo" style="display: none">
                    @include('pages.registros.formularioColaboradorSinActivo')
                </div>

                <div id="formColaboradorConActivo" style="display: none">
                    @include('pages.registros.formularioColaboradorConActivo')
                </div>

                {{-- <div>
                    @include('pages.registros.vehiculo')
                </div> --}}
                
            </div>
        </div>

        {{-- @include('pages.registros.modales') --}}
        @include('pages.modalError')

    </section>
@endsection

{{-- <form id="formulario" action="{{ route('crearVehiculo') }}" method="POST" novalidate>
                    @csrf --}}
                    {{-- <div>
                        @include('pages.registros.formularioCrear')
                    </div> --}}
                    {{-- </form> --}}