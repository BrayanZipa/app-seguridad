<div id="panel" class="card card-primary">
    <div id="cardHeader" class="card-header">
        <h3 id="tabInfoRegistro" class="card-title"></h3>
        <div class="card-tools">
            <button id="botonCollapse" type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button id="botonCerrar" type="button" class="btn btn-tool">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body mb-n4">
        <div class="row">
            <div id="columnaFoto" class="col-sm-3">
                <div id="divFotoPersona" class="form-group">
                    <label>Fotografía</label><br>
                    <img id="fotoPersona" class="img-fluid rounded" style="border: 1px solid #007bff" src="" alt="Foto persona">
                </div>
                <div id="divLogoEmpresa" class="form-group">
                    <label>Empresa</label><br>
                    <img id="logoEmpresa" class="img-fluid rounded" src="" alt="Logo empresa">
                </div>
            </div>
            <div id="columnaInformacion" class="col-sm-9">
                <label>Información del registro</label>
                <div class="card card-primary card-tabs mx-1">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabDatosIngreso" data-toggle="pill" href="#datosIngreso" role="tab" aria-controls="datosIngreso" aria-selected="true">Datos de ingreso</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabDatosBasicos" data-toggle="pill" href="#datosBasicos" role="tab" aria-controls="datosBasicos" aria-selected="false">Datos básicos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabDatosActivo" data-toggle="pill" href="#datosActivo" role="tab" aria-controls="datosActivo" aria-selected="false">Activo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabDatosVehiculo" data-toggle="pill" href="#datosVehiculo" role="tab" aria-controls="datosVehiculo" aria-selected="false">Vehículo</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent1">
                            <div class="tab-pane fade active show" id="datosIngreso" role="tabpanel" aria-labelledby="tabDatosIngreso">
                                <div class="ml-4">
                                    <div class="row">                              
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Fecha de ingreso</h5>                                         
                                                    <span id="spanFecha"></span>
                                                </div>                                         
                                            </div>
                                        </div>
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Hora de ingreso</h5>                                         
                                                    <span id="spanHora"></span>
                                                </div>                                         
                                            </div>
                                        </div>
                                        <div id="infoSalidaPersona" class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Fecha de salida</h5>
                                                            <span id="spanFechaSalida"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Hora de salida</h5>
                                                            <span id="spanHoraSalida"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                   
                                    <div class="row"> 
                                        <div id="infoVisitanteConductor" class="col-sm-6">
                                            <div class="row"> 
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Empresa que visita</h5>                                         
                                                            <span id="spanEmpresa"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>                             
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Colaborador a cargo</h5>                                         
                                                            <span id="spanColaborador"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="columnaDescripcion" class="col-sm-6">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Descripción</h5>                                         
                                                    <p id="parrafoDescripcion"></p>
                                                </div>                                         
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="tab-pane fade" id="datosBasicos" role="tabpanel" aria-labelledby="tabDatosBasicos">
                                <div class="ml-4">
                                    <div class="row">                              
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Nombre</h5>                                         
                                                    <span id="spanNombre"></span>
                                                </div>                                         
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Apellidos</h5>                                         
                                                    <span id="spanApellido"></span>
                                                </div>                                         
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Identificación</h5>                                         
                                                    <span id="spanIdentificacion"></span>
                                                </div>                                         
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 id="tituloTelefono" class="description-header mb-1"></h5>                                         
                                                    <span id="spanTelefono"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">EPS</h5>                                         
                                                    <span id="spanEps"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">ARL</h5>                                         
                                                    <span id="spanArl"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="infoColaborador" class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Empresa a la que pertenece</h5>                                         
                                                    <span id="spanEmpresaCol"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Correo empresarial</h5>                                         
                                                    <span id="spanCorreo"></span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="datosActivo" role="tabpanel" aria-labelledby="tabDatosActivo">
                                <div class="ml-4">
                                    <div class="row">
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Fecha de ingreso</h5>                                         
                                                    <span id="spanFechaActivo"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Hora de ingreso</h5>                                         
                                                    <span id="spanHoraActivo"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="infoSalidaActivo" class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Fecha de salida</h5>
                                                            <span id="spanFechaSalidaActivo"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Hora de salida</h5>
                                                            <span id="spanHoraSalidaActivo"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Tipo de activo</h5>                                         
                                                    <span id="spanTipoActivo"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="columnaPanel col-sm-3">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Activo</h5>                                        
                                                    <span id="spanCodigoActivo"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="columnaActivo" class="columnaPanel col-sm-3" style="display: none">
                                            <div class="form-group">
                                                <div class="description-block text-left">
                                                    <h5 class="description-header mb-1">Cambio de activo</h5>
                                                    <span id="spanCodigoActivo2"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divActivo" class="row mt-3 mb-n3">
                                        <div class="col-sm-12">
                                            <div class="form-group clearfix">  
                                                <div class="icheck-primary d-inline">
                                                    <label for="checkActivo">
                                                        ¿La persona sale sin activo?
                                                    </label>
                                                    <input type="checkbox" id="checkActivo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="datosVehiculo" role="tabpanel" aria-labelledby="tabDatosVehiculo">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="ml-4">
                                            <div class="row">                              
                                                <div class="columnaPanel col-sm-3">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Fecha de ingreso</h5>                                         
                                                            <span id="spanFechaVehiculo"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                                <div class="columnaPanel col-sm-3">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Hora de ingreso</h5>                                         
                                                            <span id="spanHoraVehiculo"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                                <div id="infoSalidaVehiculo" class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="description-block text-left">
                                                                    <h5 class="description-header mb-1">Fecha de salida</h5>
                                                                    <span id="spanFechaSalidaVehiculo"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="description-block text-left">
                                                                    <h5 class="description-header mb-1">Hora de salida</h5>
                                                                    <span id="spanHoraSalidaVehiculo"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                   
                                            <div class="row">                              
                                                <div class="columnaPanel col-sm-3">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Identificador</h5>                                         
                                                            <span id="spanIdentificador"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                                <div class="columnaPanel col-sm-3">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Tipo de vehículo</h5>                                         
                                                            <span id="spanTipo"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                                <div class="columnaPanel col-sm-3">
                                                    <div class="form-group">
                                                        <div class="description-block text-left">
                                                            <h5 class="description-header mb-1">Marca del vehículo</h5>                                         
                                                            <span id="spanMarca"></span>
                                                        </div>                                         
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="divVehiculo" class="row mt-3 mb-n3">
                                                <div class="col-sm-12">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline">
                                                            <label for="checkVehiculo">
                                                                ¿La persona sale sin vehículo?
                                                            </label>
                                                            <input type="checkbox" id="checkVehiculo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3 mb-n4">
                                        <div class="form-group">
                                            <label>Fotografía vehículo</label>
                                            <img id="fotoVehiculo" class="img-fluid rounded" style="border: 1px solid #fd7e14" src="" alt="Foto vehículo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="footerPanel" class="card-footer">
        <button type='button' id="botonGuardarSalida" class="btn btn-primary">Registrar salida</button>
    </div>
</div>