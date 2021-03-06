$(function() { 

    //Uso de DataTables para mostrar los registros realizados en donde se completo tanto las entradas como salidas de todos los tipos de persona (visitantes, conductores, colaboradores con y sin activo)
    $('#tabla_registros').DataTable({
        'destroy': true,
        'processing': true,
        'responsive': true,
        'autoWidth': false,
        // 'serverSide': true,
        // 'scrollY': '300px',
        'ajax': '/registros/informacion',
        'dataType': 'json',
        'type': 'GET',
        'columns': [
            {
                'data': 'id_registros',
                'name': 'id_registros'
            },
            {
                'data': 'tipopersona',
                'name': 'tipopersona'
            },
            {  
                'data': null, 
                'name': 'nombre',
                render: function ( data, type, row ) {
                    return data.nombre+' '+data.apellido;
                }
            },
            {
                'data': 'identificacion',
                'name': 'identificacion',
            },
            {
                'data': 'ingreso_persona',
                render: function (data) {
                    return moment(data).format('DD-MM-YYYY');
                } 
            },
            {
                'data': 'ingreso_persona',
                render: function (data) {
                    return moment(data).format('h:mm:ss a');
                } 
            },
            {
                'data': 'salida_persona',
                render: function (data) {
                    return moment(data).format('DD-MM-YYYY');
                } 
            },
            {
                'data': 'salida_persona',
                render: function (data) {
                    return moment(data).format('h:mm:ss a');
                } 
            }, 
            {
                'data': 'ingreso_activo',
                render: function (data, type, row) {
                    if(data != null){ return row.codigo_activo; }
                    return 'No';
                } 
            },
            {
                'data': 'ingreso_vehiculo',
                render: function (data, type, row ) {
                    if(data != null){ return row.identificador; }
                    return 'No';
                }
            },     
            {
                'data': 'name',
                'name': 'name',
                // 'searchable': false,
                // 'orderable': false
            },
            {
                'class': 'consultar_registro',
                'orderable': false,
                'data': null,
                'defaultContent': '<td>' +
                    '<div class="action-buttons text-center">' +
                    '<a href="#" class="btn btn-primary btn-icon btn-sm">' +
                    '<i class="fa-solid fa-eye"></i>' +
                    '</a>' +
                    '</div>' +
                    '</td>',
            }],
        'order': [[0, 'desc']],  
        'lengthChange': true,
        'lengthMenu': [
            [6, 10, 25, 50, 75, 100, -1],
            [6, 10, 25, 50, 75, 100, 'ALL']
        ],
        'language': {
            'lengthMenu': 'Mostrar _MENU_ registros por p??gina',
            'zeroRecords': 'No hay registros',
            'info': 'Mostrando p??gina _PAGE_ de _PAGES_',
            'infoEmpty': 'No hay registros disponibles',
            'infoFiltered': '(filtrado de _MAX_ registros totales)',
            'search': 'Buscar:',
            'paginate': {
                'next': 'Siguiente',
                'previous': 'Anterior'
            }
        },
    });

    //Funci??n que permite reestablecer las pesta??as de selecci??n (Tabs) en la vista para que sea la pesta??a inicial la primera que se muestre al momento en que se seleccione un nuevo registro para ser consultado
    function restablecerTabs() {
        if($('#tabDatosBasicos').hasClass('active') || $('#tabDatosActivo').hasClass('active') || $('#tabDatosVehiculo').hasClass('active')){
            if($('#tabDatosBasicos').hasClass('active')){
                $('#tabDatosBasicos').removeClass('active');
                $('#datosBasicos').removeClass('active show');

            } else if($('#tabDatosActivo').hasClass('active')){
                $('#tabDatosActivo').removeClass('active');
                $('#datosActivo').removeClass('active show');

            } else {
                $('#tabDatosVehiculo').removeClass('active');
                $('#datosVehiculo').removeClass('active show');
            }
            $('#tabDatosIngreso').addClass('active');
            $('#datosIngreso').addClass('active show');
        }
    }

    //Se elije una fila de la tabla de registros completados y se toma la informaci??n del registro para mostrarla en un panel de pesta??as de selecci??n de manera organizada dependiendo del tipo de persona
    $('#tabla_registros tbody').on('click', '.consultar_registro', function () { 
        var data = $('#tabla_registros').DataTable().row(this).data(); 
        restablecerTabs();

        if(data.ingreso_vehiculo != null){
            $('#divVehiculo').css('display', 'none');
            $('#fotoVehiculo').attr('src', data.foto_vehiculo);
            $('#spanFechaVehiculo').text(moment(data.ingreso_vehiculo).format('DD-MM-YYYY'));
            $('#spanHoraVehiculo').text(moment(data.ingreso_vehiculo).format('h:mm:ss a'));

            if(data.salida_vehiculo != null){
                $('#spanFechaSalidaVehiculo').text(moment(data.salida_vehiculo).format('DD-MM-YYYY'));
                $('#spanHoraSalidaVehiculo').text(moment(data.salida_vehiculo).format('h:mm:ss a'));
            } 
            
            $('#spanIdentificador').text(data.identificador);
            $('#spanTipo').text(data.tipo);  
            $('#spanMarca').text(data.marca);   
            $('#tabDatosVehiculo').css('display', 'block');
        } else {
            $('#tabDatosVehiculo').css('display', 'none');
        }

        if(data.ingreso_activo != null){
            $('#divActivo').css('display', 'none');
            $('#spanFechaActivo').text(moment(data.ingreso_activo).format('DD-MM-YYYY'));
            $('#spanHoraActivo').text(moment(data.ingreso_activo).format('h:mm:ss a'));

            if(data.salida_activo != null){
                $('#spanFechaSalidaActivo').text(moment(data.salida_activo).format('DD-MM-YYYY'));
                $('#spanHoraSalidaActivo').text(moment(data.salida_activo).format('h:mm:ss a'));
            }
            
            $('#spanTipoActivo').text(data.activo);
            $('#spanCodigoActivo').text(data.codigo_activo);
            if(data.codigo_activo_salida != null){
                $('#spanCodigoActivo2').text(data.codigo_activo_salida); 
                $('#columnaActivo').css('display', '');
            }  else {
                $('#columnaActivo').css('display', 'none');
            }
            $('#tabDatosActivo').css('display', 'block');
        } else {
            $('#tabDatosActivo').css('display', 'none');
        }

        $('#spanFecha').text(moment(data.ingreso_persona).format('DD-MM-YYYY'));
        $('#spanHora').text(moment(data.ingreso_persona).format('h:mm:ss a'));
        $('#spanFechaSalida').text(moment(data.salida_persona).format('DD-MM-YYYY'));
        $('#spanHoraSalida').text(moment(data.salida_persona).format('h:mm:ss a'));
        $('#spanNombre').text(data.nombre);
        $('#spanApellido').text(data.apellido);
        $('#spanIdentificacion').text(data.identificacion);
        $('#spanTelefono').text(data.tel_contacto);
        $('#spanEps').text(data.eps);
        $('#spanArl').text(data.arl); 
        $('#parrafoDescripcion').text(data.descripcion);

        if(data.id_tipo_persona == 1 || data.id_tipo_persona == 4){
            if($('#columnaFoto').hasClass('col-sm-2')){
                $('#columnaFoto').removeClass('col-sm-2');
                $('#columnaFoto').addClass('col-sm-3');
                $('#columnaInformacion').removeClass('col-sm-10');
                $('#columnaInformacion').addClass('col-sm-9'); 
            }     
            $('#infoColaborador').css('display', 'none');            
            $('#spanEmpresa').text(data.empresavisitada); 
            $('#spanColaborador').text(data.colaborador);
            $('#infoVisitanteConductor').css('display', '');  
            
            $('#divLogoEmpresa').css('display', 'none');
            $('#fotoPersona').attr('src', data.foto).on('load', function() {
                $('#divFotoPersona').css('display', 'block');
            });

            if(data.id_tipo_persona == 1 ){
                $('#tabInfoRegistro').text('Registro visitante');
                $('#tituloTelefono').text('Tel??fono de emergencia'); 
            } else {
                $('#tabInfoRegistro').text('Registro conductor');
                $('#tituloTelefono').text('Tel??fono de contacto'); 
            }

        } else if(data.id_tipo_persona == 3 || data.id_tipo_persona == 2){
            if($('#columnaFoto').hasClass('col-sm-3')){
                $('#columnaFoto').removeClass('col-sm-3');
                $('#columnaFoto').addClass('col-sm-2');
                $('#columnaInformacion').removeClass('col-sm-9');
                $('#columnaInformacion').addClass('col-sm-10');
            }
            $('#infoVisitanteConductor').css('display', 'none'); 
            $('#tabInfoRegistro').text('Registro colaborador');
            $('#tituloTelefono').text('Tel??fono de contacto'); 
            $('#spanCorreo').text(data.email); 
            $('#spanEmpresaCol').text(data.empresa);
            $('#infoColaborador').css('display', '');

            var urlLogo = '/assets/imagenes/' + data.empresa.toLowerCase() +'.png';
            $('#divFotoPersona').css('display', 'none');
            $('#logoEmpresa').attr('src', urlLogo).on('load', function() {
                $('#divLogoEmpresa').css('display', 'block');
            }); 
        }

        $('#footerPanel').css('display', 'none');  
        $('#informacionRegistro').css('display', 'block');  
    });

    //Bot??n que al ser seleccionado elimina el bot??n registrar salida si se encuentra creado en la vista
    $('#botonCollapse').click(function(){
        if($('#footerPanel').length){ $('#footerPanel').remove(); }      
    });

    //Bot??n que permite ocultar el panel de informaci??n del registro de la persona si selecciono para visualizar la informaci??n
    $('#botonCerrar').click(function(){
        $('#informacionRegistro').css('display', 'none'); 
    });

});