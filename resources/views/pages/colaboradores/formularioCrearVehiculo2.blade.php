    <div class="card card-orange mb-n4 mx-n1" style="margin-top: 32px">
        <div class="card-header pb-1">
            <h3 class="card-title">Crear nuevo vehículo</h3>
            <div class="card-tools">
                <button id="botonComprimirVehiculo" type="button" class="btn btn-tool pb-2" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button id="botonCerrar2" type="button" class="btn btn-tool pb-2 mr-n3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="inputFotoVehiculo2">Fotografía</label>

                        <input type="hidden" id="inputFotoVehiculo2" class="{{ $errors->has('foto_vehiculo') && old('casoIngreso2') != '' ? 'is-invalid' : '' }}" name="foto_vehiculo"
                            value="{{ old('casoIngreso2') != '' ? old('foto_vehiculo') : ''}}">

                        <video src="" id="video2" class="img-fluid rounded" style="display: none"></video>
                        <canvas id="canvas2" class="img-fluid rounded" style="display: none"></canvas>

                        @if ($errors->has('foto_vehiculo')) 
                            <div class="invalid-feedback">
                                {{ $errors->first('foto_vehiculo') }}
                            </div>            
                        @endif

                        <div class="mt-2">
                            <button id="botonActivar2" type="button" class="btn btn-sm"
                                style="background-color: rgb(255, 115, 0)">Activar</button>
                            <button id="botonCapturar2" type="button" class="btn btn-sm"
                                style="display: none">Capturar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="inputNumeroIdentificador2">Ingrese el número identificador del vehículo</label>
                                <input type="text" class="vehiculo2 form-control {{ $errors->has('identificador') && old('casoIngreso2') != '' ? 'is-invalid' : '' }}" id="inputNumeroIdentificador2"
                                    name="identificador" value="{{ old('casoIngreso2') != '' ? old('identificador') : ''}}" autocomplete="off"
                                    placeholder="Número identificador" required>
                                    @if ($errors->has('identificador')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('identificador') }}
                                        </div>          
                                    @endif  
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="selectTipoVehiculo2">Ingrese el tipo de vehículo</label>
                                <select id="selectTipoVehiculo2" class="select2tipo vehiculo2 form-control {{ $errors->has('id_tipo_vehiculo') && old('casoIngreso2') != '' ? 'is-invalid' : '' }}"
                                    style="width: 100%;" name="id_tipo_vehiculo" required>
                                    <option selected="selected" value="" disabled></option>
                                    @foreach ($tipoVehiculos as $tipoVehiculo)
                                        <option value="{{ $tipoVehiculo->id_tipo_vehiculos }}"
                                            {{ $tipoVehiculo->id_tipo_vehiculos == old('id_tipo_vehiculo') && old('casoIngreso2') != '' ? 'selected' : '' }}>
                                            {{ $tipoVehiculo->tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('id_tipo_vehiculo')) 
                                    <div class="invalid-feedback">
                                        {{ $errors->first('id_tipo_vehiculo') }}
                                    </div>            
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="selectMarcaVehiculo2">Ingrese la marca del vehículo</label>
                                <select id="selectMarcaVehiculo2" class="select2marca form-control" style="width: 100%;" name="id_marca_vehiculo">
                                    <option selected="selected" value="" disabled></option>
                                    @foreach ($marcaVehiculos as $marcaVehiculo)
                                        <option value="{{ $marcaVehiculo->id_marca_vehiculos }}"
                                            {{ $marcaVehiculo->id_marca_vehiculos == old('id_marca_vehiculo') && old('casoIngreso2') != '' ? 'selected' : '' }}>
                                            {{ $marcaVehiculo->marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer mt-n4">
            <button id="botonCrear4" type='submit' class="btn" style="background-color: rgb(255, 115, 0)">Crear todo</button>
            <button id="botonLimpiar4" type='button' class="btn btn-secondary">Limpiar</button>
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->