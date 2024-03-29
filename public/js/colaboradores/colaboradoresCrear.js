$(function() {

    //Cuando se selecciona en el menú a nuevo colaborador con activo dependiendo si esta selecionado el vehículo o no cambia el caso de ingreso de información
    $('#colaboradorConActivo').click(function() {
        if($('#checkVehiculo').prop('checked') == true){
            $('#casoIngreso').val('conActivoVehiculo');
        } else {
            $('#casoIngreso').val('');
        }
        $('#casoIngreso2').val('');
    });
    
    //Cuando se selecciona en el menú a nuevo colaborador sin activo dependiendo si esta selecionado el vehículo o no cambia el caso de ingreso de información
    $('#colaboradorSinActivo').click(function() {
        if($('#checkVehiculo2').prop('checked') == true){
            $('#casoIngreso2').val('sinActivoVehiculo');
        } else {
            $('#casoIngreso2').val('colaboradorSinActivo');
        }
        $('#casoIngreso').val('');
    });

    //Permite que a los select de selección de identificación, EPS y ARL se les asigne una barra de búsqueda haciendolos más dinámicos
    function activarSelect2Colaborador() {
        $('#selectCodigo').select2({
            theme: 'bootstrap4',
            placeholder: 'Buscar el activo del colaborador en el sistema GLPI',
            language: {
                noResults: function() {
                    return 'No hay resultado';
                }
            }
        });
        $('#selectPersona').select2({
            theme: 'bootstrap4',
            placeholder: 'Buscar colaborador si ya esta creado como visitante',
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

    //Permite que a los select de selección Tipo de vehículo y Marca de vehículo se les asigne una barra de búsqueda haciendolos más dinámicos            
    function activarSelect2Vehiculo() {
        $('.select2tipo').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione el tipo',
            language: {
                noResults: function() {
                    return 'No hay resultado';
                }
            }
        });
        $('.select2marca').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione la marca',
            language: {
                noResults: function() {
                    return 'No hay resultado';
                }
            }
        });
    }

    activarSelect2Vehiculo();
    activarSelect2Colaborador();

    //Función que permite traer los datos de un colaborador y los datos del activo que tiene asignado desde GLPI cuando se selecciona un código en el formulario de la vista de colaborador con activo, una vez traidos se colocan automáticamente en su respectivo input
    $('#selectCodigo').change(function() {
        var idActivo = $('#selectCodigo option:selected');

        $.ajax({
            url: 'computer',
            type: 'GET',
            data: {
                activo: idActivo.val(),
            },
            dataType: 'json',
            success: function(response) {  
                if ('name' in response) {
                    $('#botonLimpiar').trigger('click');
                    $('#inputCodigo').val(response['name']);

                    if(response.autorizacion != null){
                        $('#autorizacion').css({
                            'color': '#4ae11e', 
                            'font-size': '16px', 
                        });
                        $('#autorizacion').text(response.autorizacion);

                        $.ajax({
                            url: 'persona',
                            type: 'GET',
                            data: {
                                colaborador: response.users_id,
                            },
                            dataType: 'json',
                            success: function(colaborador) {
                                $('#inputNombre').val(colaborador['firstname']);
                                $('#inputApellido').val(colaborador['realname']);
                                $('#inputIdentificacion').val(colaborador['registration_number']);
                                $('#inputEmail').val(colaborador['email']);
    
                                if (colaborador['phone2'].includes('Aviomar')) {
                                    $('#selectEmpresa').val(1);
                                } else if (colaborador['phone2'].includes('Snider')) {
                                    $('#selectEmpresa').val(2);
                                } else if (colaborador['phone2'].includes('Colvan')) {
                                    $('#selectEmpresa').val(3);
                                }      
                            },
                            error: function() {
                                console.log('Error obteniendo los datos de GLPI');
                            }
                        });

                    } else {
                        $('#autorizacion').css({
                            'color': '#dc3545', 
                            'font-size': '16px', 
                        });
                        $('#autorizacion').text('El activo ' + response['name'] + ' no tiene autorización para ser retirado de la empresa');
                    }
                    $('#inputAutorizacion').val($('#autorizacion').text());

                } else {
                    $('#botonLimpiar').trigger('click');
                    $('#inputCodigo').addClass('is-invalid');
                    $('#inputCodigo').val('*Activo eliminado del sistema GLPI');
                    $('#autorizacion').text('');  
                    $('#inputAutorizacion').val($('#autorizacion').text());                   
                }  
            },
            error: function() {
                console.log('Error obteniendo los datos de GLPI');
            }
        });
    });

    //Función que permite trae los datos de una persona de tipo visitante de la base de datos cuando se selecciona una identificación en el formulario de la vista de colaborador sin activo, una vez traidos se colocan automáticamente en su respectivo input
    $('#selectPersona').change(function() {
        $.ajax({
            url: 'personacreada',
            type: 'GET',
            data: {
                persona: $('#selectPersona option:selected').val()
            },
            dataType: 'json',
            success: function(response) {
                $('#botonLimpiar3').trigger('click');
                $('#inputNombre2').val(response['nombre']);
                $('#inputApellido2').val(response['apellido']);
                $('#inputIdentificacion2').val(response['identificacion']);
                $('#inputTelefono2').val(response['tel_contacto']);
                $('#selectEps2').val(response['id_eps']);
                $('#selectArl2').val(response['id_arl']);
                $('#selectEmpresa2').val(response['id_empresa']);
                activarSelect2Colaborador();
            }, 
            error: function() {
                console.log('Error al traer los datos de la base de datos');
            }
        });
    });

    //Manejo del checkbox que muestra el formulario de crear vehículo si es seleccionado en la vista de colaborador con activo
    $('#checkVehiculo').on('change', function() {
        if ($('#checkVehiculo').is(':checked')) {
            $('#botonComprimirColaborador').trigger('click');
            if($('#inputFotoVehiculo').val() == ''){
                $('#botonActivar').trigger('click');
            }
            $('#crearVehiculo').css('display', 'block');
            $('#botonCrear').css('display', 'none');
            $('#checkVehiculo').prop('disabled', true);
            $('#casoIngreso').val('conActivoVehiculo');
            requiredTrue('.vehiculo');
        }
    });

    //Manejo del checkbox que muestra el formulario de crear vehículo si es seleccionado en la vista de colaborador sin activo
    $('#checkVehiculo2').on('change', function() {
        if ($('#checkVehiculo2').is(':checked')) {
            $('#botonComprimirColaborador2').trigger('click');
            if($('#inputFotoVehiculo2').val() == ''){
                $('#botonActivar2').trigger('click');
            }
            $('#crearVehiculo2').css('display', 'block');
            $('#botonCrear3').css('display', 'none');
            $('#checkVehiculo2').prop('disabled', true);
            $('#casoIngreso2').val('sinActivoVehiculo');
            requiredTrue('.vehiculo2');
        }
    });

    //Manejo del botón eliminar del formulario de Vehiculo en la vista de colaborador con activo
    $('#botonCerrar').click(function() {
        $('#botonComprimirColaborador').trigger('click');
        $('#botonCrear').css('display', 'inline');
        $('#casoIngreso').val('');
        $('#crearVehiculo').css('display', 'none');
        $('#botonLimpiar2').trigger('click');
        $('#checkVehiculo').prop('disabled', false);
        $('#checkVehiculo').prop('checked', false);
        requiredFalse('.vehiculo');
    });

    //Manejo del botón eliminar del formulario de Vehiculo en la vista de colaborador sin activo
    $('#botonCerrar2').click(function() {
        $('#botonComprimirColaborador2').trigger('click');
        $('#botonCrear3').css('display', 'inline');
        $('#casoIngreso2').val('colaboradorSinActivo');
        $('#crearVehiculo2').css('display', 'none');
        $('#botonLimpiar4').trigger('click');
        $('#checkVehiculo2').prop('disabled', false);
        $('#checkVehiculo2').prop('checked', false);
        requiredFalse('.vehiculo2');
    });

    //Botón que limpia la información del formulario de colaborador en la vista de colaborador con activo
    $('#botonLimpiar').click(function() {
        $('#autorizacion').text('');
        $('.colaborador').each(function(index) {
            $(this).val('');
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
            }
        });
        activarSelect2Colaborador();
    });

    //Botón que limpia la información del formulario de vehículo en la vista de colaborador con activo
    $('#botonLimpiar2').click(function() {
        document.getElementById('inputFotoVehiculo').setAttribute('value', '');
        $('#botonActivar').trigger('click');
        $('.vehiculo').each(function(index) {
            $(this).val('');
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
            }
        });
        $('#selectMarcaVehiculo').val([]);
        activarSelect2Vehiculo();
    });

    //Botón que limpia la información del formulario de colaborador en la vista de colaborador sin activo
    $('#botonLimpiar3').click(function() {
        $('.colaborador2').each(function(index) {
            $(this).val('');
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
            }
        });
        activarSelect2Colaborador();
    });

    //Botón que limpia la información del formulario de vehículo en la vista de colaborador sin activo
    $('#botonLimpiar4').click(function() {
        document.getElementById('inputFotoVehiculo2').setAttribute('value', '');
        $('#botonActivar2').trigger('click');
        $('.vehiculo2').each(function(index) {
            $(this).val('');
            if($(this).hasClass('is-invalid')){
                $(this).removeClass('is-invalid');
            } 
        });
        $('#selectMarcaVehiculo2').val([]);
        activarSelect2Vehiculo();   
    });

    //Al iniciar la página inhabilita la propiedad required del formulario de Vehiculo mientras no sea seleccionado por el usuario en la vista colaborador con activo
    if ($('#crearVehiculo').is(':hidden')) {
        requiredFalse('.vehiculo');
    }

    //Al iniciar la página inhabilita la propiedad required del formulario de Vehiculo mientras no sea seleccionado por el usuario en la vista colaborador sin activo
    if ($('#crearVehiculo2').is(':hidden')) {
        requiredFalse('.vehiculo2');
    }

    //Función que permite volver verdadera la propiedad required de los formularios  
    function requiredTrue(clase) {
        $(clase).each(function(index) {
            $(this).prop('required', true);
        });
    }

    //Función que permite volver falsa la propiedad required de los formularios
    function requiredFalse(clase) {
        $(clase).each(function(index) {
            $(this).prop('required', false);
        });
    }

    //Botón que da acceso a la cámara web del computador donde este abierta la aplicación desde el formulario ingresar vehículo en la vista nuevo colaborador con activo
    $('#botonActivar').click(function() {
        document.getElementById('canvas').style.display = 'none';
        document.getElementById('inputFotoVehiculo').setAttribute('value', '');
        const video = document.getElementById('video');

        if (!tieneSoporteUserMedia()) {
            alert('Lo siento. Tu navegador no soporta esta característica');
            return;
        }

        const constraints = {
            audio: false,
            video: { width: 640, height: 500 }
        }

        const navegador = navigator.userAgent;
        if (navegador.match(/Android/i) || navegador.match(/webOS/i) || navegador.match(/iPhone/i) || navegador.match(/iPad/i) || navegador.match(/iPod/i) || navegador.match(/BlackBerry/i) || navegador.match(/Windows Phone/i)) {    
            constraints.video.facingMode = {
                exact: 'environment'
            }
        }

        navigator.mediaDevices.getUserMedia(constraints)
            .then((stream) => {                       
                video.style.display = 'block';
                video.style.borderStyle = 'solid';
                video.style.borderWidth = '1px';
                video.style.borderColor = '#fd7e14';

                video.srcObject = stream;
                video.play(); 

                document.getElementById('botonCapturar').style.backgroundColor = 'rgb(255, 115, 0)'; 
                document.getElementById('botonActivar').style.display = 'inline';  
                document.getElementById('botonCapturar').style.display = 'inline';                      
            })
            .catch((err) => console.log(err))            
    });

    //Botón que da acceso a la cámara web del computador donde este abierta la aplicación desde el formulario ingresar vehículo en la vista nuevo colaborador sin activo
    $('#botonActivar2').click(function() {
        document.getElementById('canvas2').style.display = 'none';
        document.getElementById('inputFotoVehiculo2').setAttribute('value', '');
        const video2 = document.getElementById('video2');

        if (!tieneSoporteUserMedia()) {
            alert('Lo siento. Tu navegador no soporta esta característica');
            return;
        }

        const constraints = {
            audio: false,
            video: { width: 640, height: 500 }
        }

        const navegador = navigator.userAgent;
        if (navegador.match(/Android/i) || navegador.match(/webOS/i) || navegador.match(/iPhone/i) || navegador.match(/iPad/i) || navegador.match(/iPod/i) || navegador.match(/BlackBerry/i) || navegador.match(/Windows Phone/i)) {    
            constraints.video.facingMode = {
                exact: 'environment'
            }
        }

        navigator.mediaDevices.getUserMedia(constraints)
            .then((stream) => {
                video2.style.display = 'block';
                video2.style.borderStyle = 'solid';
                video2.style.borderWidth = '1px';
                video2.style.borderColor = '#fd7e14';

                video2.srcObject = stream;
                video2.play();

                document.getElementById('botonCapturar2').style.backgroundColor = 'rgb(255, 115, 0)';
                document.getElementById('botonActivar2').style.display = 'inline'; 
                document.getElementById('botonCapturar2').style.display = 'inline';
            })
            .catch((err) => console.log(err))
    });

    // Función que permite saber si el navegador que se esta utilizando soporta características audio visuales
    function tieneSoporteUserMedia() {
        return !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices
                .getUserMedia) ||
            navigator.webkitGetUserMedia || navigator.msGetUserMedia)
    }

    //Botón que captura una fotografía desde el formulario de crear vehículo en la vista nuevo colaborador con activo
    $('#botonCapturar').click(function() {
        var inputFotoVehiculo = document.getElementById('inputFotoVehiculo');
        if(inputFotoVehiculo.classList.contains( 'is-invalid' )){
            inputFotoVehiculo.classList.remove('is-invalid');
        }
        var video = document.getElementById('video');
        video.pause();
        var canvas = document.getElementById('canvas');
        var contexto = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        contexto.drawImage(video, 0, 0, canvas.width, canvas.height); 

        var foto = canvas.toDataURL();
        document.getElementById('inputFotoVehiculo').setAttribute('value', foto);  
    });

    //Botón que captura una fotografía desde el formulario de crear vehículo en la vista nuevo colaborador con vehículo
    $('#botonCapturar2').click(function() {
        var inputFotoVehiculo = document.getElementById('inputFotoVehiculo2');
        if (inputFotoVehiculo.classList.contains('is-invalid')) {
            inputFotoVehiculo.classList.remove('is-invalid');
        }
        var video2 = document.getElementById('video2');
        video2.pause();
        var canvas2 = document.getElementById('canvas2');
        var contexto2 = canvas2.getContext('2d');
        canvas2.width = video2.videoWidth;
        canvas2.height = video2.videoHeight;
        contexto2.drawImage(video2, 0, 0, canvas2.width, canvas2.height);

        var foto = canvas2.toDataURL();
        document.getElementById('inputFotoVehiculo2').setAttribute('value', foto);
    });

    //Función que permite que al momento que el usuario seleccione Bicicleta o Scooter eléctrico en el formulario de ingreso de vehículo en la vista nuevo colaborador con activo se desabilite el select de marca de vehículo
    function selectMarcaVehiculo() {  
        var tipo = $('#selectTipoVehiculo option:selected').text();     
        var tipoVehiculo = tipo.replace(/\s+/g, '');

        if (tipoVehiculo == 'Bicicleta' || tipoVehiculo == 'Scootereléctrico') {
            $('#selectMarcaVehiculo').val('');
            $('#selectMarcaVehiculo').prop('disabled', true);
            $('#selectMarcaVehiculo').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione la marca',
                language: {
                    noResults: function() {
                        return 'No hay resultado';
                    }
                }
            });
        } else {
            $('#selectMarcaVehiculo').prop('disabled', false);
        }
    }

    //Función que permite que al momento que el usuario seleccione Bicicleta o Scooter eléctrico en el formulario de ingreso de vehículo en la vista nuevo colaborador sin activo se desabilite el select de marca de vehículo
    function selectMarcaVehiculo2() {  
        var tipo = $('#selectTipoVehiculo2 option:selected').text();     
        var tipoVehiculo = tipo.replace(/\s+/g, '');

        if (tipoVehiculo == 'Bicicleta' || tipoVehiculo == 'Scootereléctrico') {
            $('#selectMarcaVehiculo2').val('');
            $('#selectMarcaVehiculo2').prop('disabled', true);
            $('#selectMarcaVehiculo2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione la marca',
                language: {
                    noResults: function() {
                        return 'No hay resultado';
                    }
                }
            });
        } else {
            $('#selectMarcaVehiculo2').prop('disabled', false);
        }
    }

    //Función que se activa cuando el usuario selecciona alguna opción del select de tipo de vehículo en la vista de colaborador con activo
    $('#selectTipoVehiculo').change(function() {   
        selectMarcaVehiculo();
    });

    //Función que se activa cuando el usuario selecciona alguna opción del select de tipo de vehículo en la vista de colaborador sin activo
    $('#selectTipoVehiculo2').change(function() {
        selectMarcaVehiculo2();
    });

     // Función anónima que genera mensajes de error cuando el usuario intenta enviar algún formulario en la vista de colaborador con activo sin los datos requeridos, es una primera validación del lado del cliente
    (function() {
        'use strict'
        var form = document.getElementById('formularioColaborador');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) { 
                event.preventDefault();
                event.stopPropagation();   

                $('.colaborador, .vehiculo').each(function(index) {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                    }
                });
            }
        }, false);
    })();

    //Función anónima que genera mensajes de error cuando el usuario intenta enviar algún formulario en la vista de colaborador sin activo sin los datos requeridos, es una primera validación del lado del cliente
    (function() {
        'use strict'
        var form = document.getElementById('formularioColaborador2');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                $('.colaborador2, .vehiculo2').each(function(index) {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                    }
                });
            }
        }, false);
    })();

    //Botón que permite cambiar la ruta del formulario de crear nuevo colaborador o la ruta del formulario de nuevo colaborador con activo dependiendo del caso y después envía el formulario a dicha ruta, esto en caso de que el nuevo colaborador a crear previamente tenga un registro de ingreso
    $('#botonConfirmar2').click(function() {
        function asignarRuta(form) {
            $(form).attr('action','../colaboradores/registro_salida'); 
            $(form).submit();
        }
        if($('#colaboradorConActivo').hasClass('active')){
            asignarRuta('#formularioColaborador');
        } else {
            asignarRuta('#formularioColaborador2');
        }  
    });

    //Si en un input del cualquier formulario del módulo colaboradores esta la clase is-invalid al escribir en el mismo input se elimina esta clase 
    $('input.colaborador, textarea.colaborador, input.colaborador2, textarea.colaborador2, input.vehiculo, input.vehiculo2').keydown(function(event) {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        }
    });

    //Si en un select del cualquier formulario del módulo colaboradores esta la clase is-invalid al seleccionar algo en el mismo select se elimina esta clase 
    $('select.colaborador, select.colaborador2, select.vehiculo, select.vehiculo2').change(function() {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        };
    });

    //Función que permite mantener la fotografía tomada previamente al vehículo en la vista de colaborador con activo en caso de que haya errores al enviar el formulario crear vehículo
    function retornarFotoVehiculo() {
        var inputFotoVehiculo = document.getElementById('inputFotoVehiculo').value;
        var canvas = document.getElementById('canvas');
        var contexto = canvas.getContext('2d');

        canvas.setAttribute('width', '640');
        canvas.setAttribute('height', '500');

        canvas.style.borderStyle = 'solid';
        canvas.style.borderWidth = '1px';
        canvas.style.borderColor = '#fd7e14';

        var imagen = new Image();;
        imagen.src = inputFotoVehiculo;

        imagen.onload = function() {
            document.getElementById('canvas').style.display = 'block';
            contexto.drawImage(imagen, 0, 0, imagen.width, imagen.height);
        }
    }

    //Función que permite mantener la fotografía tomada previamente al vehículo en la vista de colaborador sin activo en caso de que haya errores al enviar el formulario crear vehículo
    function retornarFotoVehiculo2() {
        var inputFotoVehiculo2 = document.getElementById('inputFotoVehiculo2').value;   
        var canvas2 = document.getElementById('canvas2');
        var contexto2 = canvas2.getContext('2d');

        canvas2.setAttribute('width', '640');
        canvas2.setAttribute('height', '500');

        canvas2.style.borderStyle = 'solid';
        canvas2.style.borderWidth = '1px';
        canvas2.style.borderColor = '#fd7e14';

        var imagen2 = new Image();;
        imagen2.src = inputFotoVehiculo2;

        imagen2.onload = function() {
            document.getElementById('canvas2').style.display = 'block';
            contexto2.drawImage(imagen2, 0, 0, imagen2.width, imagen2.height);
        }
    }

    //Función que permite devolver la información de la autorización de la salida de un activo en la vista, esto en caso de que se cometan errores al momento de enviar un formulario o en caso de que se devuelva la alerta que notifica que el colaborador a crear ya tiene un registro de ingreso 
    function devolverAutorizacion() {
        if($('#inputAutorizacion').val().includes('no')){
            $('#autorizacion').css({
                'color': '#dc3545', 
                'font-size': '16px', 
            });
        } else {
            $('#autorizacion').css({
                'color': '#4ae11e', 
                'font-size': '16px', 
            });
        }
        $('#autorizacion').text($('#inputAutorizacion').val());
    }

    //Función anónima que se ejecuta si alguno de los elementos mencionados se crea en la interfaz debido a errores cometidos en el ingreso de los formularios del módulo de colaboradores
    (function() {
        if (!!document.getElementById('botonRetorno')) {
            var caso = document.getElementById('casoIngreso').value;
            var caso2 = document.getElementById('casoIngreso2').value;  
            devolverAutorizacion();

            if(caso == 'conActivoVehiculo'){
                if($('#inputFotoVehiculo').val() != ''){
                    retornarFotoVehiculo();
                    document.getElementById('botonActivar').style.display = ''; 
                } else {
                    document.getElementById('botonActivar').click();
                }
                selectMarcaVehiculo();
                $('#crearVehiculo').css('display', 'block');
                $('#botonCrear').css('display', 'none');
                $('#checkVehiculo').prop('disabled', true);
                $('#checkVehiculo').prop('checked', true);
                requiredTrue('.vehiculo');

            } else if(caso2 == 'sinActivoVehiculo'){
                if($('#inputFotoVehiculo2').val() != ''){
                    retornarFotoVehiculo2();
                    document.getElementById('botonActivar2').style.display = ''; 
                } else {
                    document.getElementById('botonActivar2').click();
                }
                selectMarcaVehiculo2();
                $('#crearVehiculo2').css('display', 'block');
                $('#botonCrear3').css('display', 'none');
                $('#checkVehiculo2').prop('disabled', true);
                $('#checkVehiculo2').prop('checked', true);
                requiredTrue('.vehiculo2');
            }
        } else if (!!document.getElementById('botonRetorno2')) {
            var caso = document.getElementById('casoIngreso').value;
            var caso2 = document.getElementById('casoIngreso2').value;

            if(caso == 'conActivoVehiculo'){
                if($('#inputFotoVehiculo').val() != ''){
                    retornarFotoVehiculo();
                    document.getElementById('botonActivar').style.display = ''; 
                }
                selectMarcaVehiculo();
                document.getElementById('checkVehiculo').click();

            } else if(caso2 == 'sinActivoVehiculo') {
                if($('#inputFotoVehiculo2').val() != ''){
                    retornarFotoVehiculo2();
                    document.getElementById('botonActivar2').style.display = ''; 
                }
                selectMarcaVehiculo2();
                document.getElementById('checkVehiculo2').click();
            }
        }
    })();

    (function() {
        if (!!document.getElementById('modalCambioRol2')) {
            devolverAutorizacion();
        }
    })();   

    //Muestra los modales de ingreso correcto dependiendo de que formularios se hayan ingresado y redirecciona en caso de que se oprima el botón continuar
    $('#modal-editar-colaborador').modal('show');
    $('#modal-editar-colaboradorActivo').modal('show');
    $('#modal-editar-colaboradorVehiculo').modal('show');
    $('#modal-editar-colaboradorVehiculoActivo').modal('show');
    $('#modal-crear-colaborador').modal('show');
    $('#modal-crear-colaboradorActivo').modal('show');
    $('#modal-crear-colaboradorVehiculo').modal('show');
    $('#modal-crear-colaboradorVehiculoActivo').modal('show');
    
    $('.botonContinuar').click(function() {
        $(location).attr('href', '../colaboradores/con_activo');
    });

    $('.botonContinuar2').click(function() {
        $(location).attr('href', '../colaboradores/sin_activo');
    });

    //Muestra los modales de registro de salida correcto cuando un visitante o colaborador pasa a ser colaborador con activo y redirecciona en caso de que se oprima el botón continuar
    $('#modalCambioRol2').modal('show');
    $('#modal-registrar-salida').modal('show');

    $('#botonSalida').click(function() {
        $(location).attr('href', '../registros/completados');
    });
    
});