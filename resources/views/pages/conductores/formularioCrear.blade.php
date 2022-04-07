<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Crear nuevo conductor</h3>
        <div class="card-tools">
            <button id="botonComprimirConductor" type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="inputFoto">Fotografía</label>

                    <input type="text" id="inputFoto" class="{{ $errors->has('foto') ? 'is-invalid' : '' }}" name="foto" value="{{ old('foto') }}"
                        style="display: none">

                    <video src="" id="video" class="img-fluid rounded" style="display: none"></video>
                    <canvas id="canvas" class="img-fluid rounded" style="display: none"></canvas>

                    @if ($errors->has('foto')) 
                        <div class="invalid-feedback">
                            {{ $errors->first('foto') }}
                        </div>            
                    @endif

                    <div class="mt-2">
                        <button id="botonActivar" type="button" class="btn btn-primary btn-sm">Activar</button>
                        <button id="botonCapturar" type="button" class="btn btn-primary btn-sm"
                            style="display: none">Capturar</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputNombre">Ingrese el nombre</label>
                            <input type="text" class="conductor form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" id="inputNombre" name="nombre"
                                value="{{ old('nombre') }}" placeholder="Nombre" autofocus required>
                                @if ($errors->has('nombre')) 
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre') }}
                                    </div>            
                                @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputApellido">Ingrese el apellido</label>
                            <input type="text" class="conductor form-control {{ $errors->has('apellido') ? 'is-invalid' : '' }}" id="inputApellido" name="apellido"
                                value="{{ old('apellido') }}" placeholder="Apellido" required>
                                @if ($errors->has('apellido')) 
                                    <div class="invalid-feedback">
                                        {{ $errors->first('apellido') }}
                                    </div>            
                                @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputIdentificacion">Ingrese la identificación</label>
                            <input type="text" class="conductor form-control {{ $errors->has('identificacion') ? 'is-invalid' : '' }}" id="inputIdentificacion"
                                name="identificacion" value="{{ old('identificacion') }}" placeholder="Identificación"
                                required>
                                @if ($errors->has('identificacion')) 
                                    <div class="invalid-feedback">
                                        {{ $errors->first('identificacion') }}
                                    </div>          
                                @endif  
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputTelefono">Ingrese un teléfono en caso de emergencia</label>
                            <input type="tel" class="conductor form-control {{ $errors->has('tel_contacto') ? 'is-invalid' : '' }}" id="inputTelefono" name="tel_contacto"
                                value="{{ old('tel_contacto') }}" placeholder="Teléfono" required>
                                @if ($errors->has('tel_contacto')) 
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tel_contacto') }}
                                    </div>          
                                @endif  
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ingrese la EPS</label>
                            <select id="selectEps" class="conductor select2bs4 form-control {{ $errors->has('id_eps') ? 'is-invalid' : '' }}" style="width: 100%;"
                                name="id_eps" required>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ingrese el ARL</label>
                            <select id="selectArl" class="conductor select2bs4 form-control {{ $errors->has('id_arl') ? 'is-invalid' : '' }}" style="width: 100%;"
                                name="id_arl" required>
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
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
