<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="inputFotoVehiculo">Fotografía</label>

            <input type="text" id="inputFotoVehiculo" class="{{ $errors->has('foto_vehiculo') ? 'is-invalid' : '' }}"
                name="foto_vehiculo" value="{{ old('foto_vehiculo') }}" style="display: none">

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
                <button id="botonCapturar2" type="button" class="btn btn-sm" style="display: none">Capturar</button>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="inputNombre">Ingrese el número identificador del vehículo</label>
                    <input type="text"
                        class="vehiculo form-control {{ $errors->has('identificador') ? 'is-invalid' : '' }}"
                        id="inputNumeroIdentificador" name="identificador" value="{{ old('identificador') }}"
                        autocomplete="off" placeholder="Número indetificador" required>
                    @if ($errors->has('identificador'))
                        <div class="invalid-feedback">
                            {{ $errors->first('identificador') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Ingrese el tipo de vehículo</label>
                    <select id="selectTipoVehiculo"
                        class="vehiculo  select2bs4 form-control {{ $errors->has('id_tipo_vehiculo') ? 'is-invalid' : '' }}"
                        style="width: 100%;" name="id_tipo_vehiculo" required>
                        <option selected="selected" value="" disabled></option>
                        @foreach ($tipoVehiculos as $tipoVehiculo)
                            <option value="{{ $tipoVehiculo->id_tipo_vehiculos }}"
                                {{ $tipoVehiculo->id_tipo_vehiculos == old('id_tipo_vehiculo') ? 'selected' : '' }}>
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
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Ingrese la marca del vehículo</label>
                    <select id="selectMarcaVehiculo" class="form-control select2bs4" style="width: 100%;"
                        name="id_marca_vehiculo">
                        <option selected="selected" value="" disabled></option>
                        @foreach ($marcaVehiculos as $marcaVehiculo)
                            <option value="{{ $marcaVehiculo->id_marca_vehiculos }}"
                                {{ $marcaVehiculo->id_marca_vehiculos == old('id_marca_vehiculo') ? 'selected' : '' }}>
                                {{ $marcaVehiculo->marca }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="float-right">
            <button id="botonLimpiar2" type='button' class="btn btn-secondary">Limpiar</button>
            <button id="botonCrear2" type='submit' class="btn" style="background-color: rgb(255, 115, 0)">Crear todo</button>      
        </div>
    </div>
</div>


{{-- <div class="row" style="display:none">
    <div class="col-sm-4">
        <div class="form-group">
            <div class="form-group">
                <input class="form-control select2" type="search">
            </div>
            <label>Ingrese al propietario del vehículo</label>
            <select class="form-control select2" style="width: 100%;" name="id_persona" disabled required>
                <option selected="selected" value="" disabled>Seleccione al propietario</option>
                @foreach ($personas as $persona)
                    <option value="{{ $persona->id_personas }}">{{ $persona->nombre }} {{ $persona->apellido }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div> --}}
