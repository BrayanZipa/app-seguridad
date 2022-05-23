<div class="row mb-n2">
    <div class="col-md-12">
        <form id="form_EditarColaborador" action="" method="POST" novalidate>
            @csrf
            @method('PUT')
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Actualizar colaborador</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i>
                        </button>
                        <button id="botonCerrar" type="button" class="btn btn-tool">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->

                <div class="card-body mb-n4">
                    <input type="hidden" id="inputId" name="id_personas" value="{{ old('id_personas') }}">
                    <div class="row">
                        {{-- <div class="col-sm-4">
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
                        </div>  --}}

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputNombre">Nombre</label>
                                <input type="text" class="colaborador form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" id="inputNombre" name="nombre" value="{{ old('nombre') }}"
                                    placeholder="Nombre" autocomplete="off" required>
                                    @if ($errors->has('nombre')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre') }}
                                        </div>            
                                    @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputApellido">Apellido</label>
                                <input type="text" class="colaborador form-control {{ $errors->has('apellido') ? 'is-invalid' : '' }}" id="inputApellido" name="apellido" value="{{ old('apellido') }}"
                                    placeholder="Apellido" autocomplete="off" required>
                                    @if ($errors->has('apellido')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('apellido') }}
                                        </div>            
                                    @endif
                            </div>
                        </div> 
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputIdentificacion">Identificación</label>
                                <input type="text" class="colaborador form-control {{ $errors->has('identificacion') ? 'is-invalid' : '' }}" id="inputIdentificacion" name="identificacion" value="{{ old('identificacion') }}" placeholder="Identificación" autocomplete="off" required>
                                    @if ($errors->has('identificacion')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('identificacion') }}
                                        </div>          
                                    @endif  
                            </div>
                        </div>  
                    </div>
                    <div class="row">          
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputEmail">Actualizar correo empresarial</label>
                                <input type="text" class="colaborador form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="inputEmail" name="email" value="{{ old('email') }}"
                                    placeholder="Correo empresarial" autocomplete="off">
                                    @if ($errors->has('email')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>          
                                    @endif  
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputTelefono">Actualizar teléfono</label>
                                <input type="tel" class="colaborador form-control {{ $errors->has('tel_contacto') ? 'is-invalid' : '' }}" id="inputTelefono" name="tel_contacto" value="{{ old('tel_contacto') }}"
                                    placeholder="Teléfono" autocomplete="off" required>
                                    @if ($errors->has('tel_contacto')) 
                                        <div class="invalid-feedback">
                                            {{ $errors->first('tel_contacto') }}
                                        </div>          
                                    @endif  
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="selectEps">Actualizar EPS</label>
                                <select id="selectEps" class="select2bs4 colaborador form-control {{ $errors->has('id_eps') ? 'is-invalid' : '' }}" style="width: 100%;" name="id_eps" required>
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
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="selectArl">Actualizar ARL</label>
                                <select id="selectArl" class="select2bs4 colaborador form-control {{ $errors->has('id_arl') ? 'is-invalid' : '' }}" style="width: 100%;" name="id_arl" required>
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
                                <label for="selectEmpresa">Actualizar empresa a la que pertenece</label>
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

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type='submit' class="btn btn-primary">Actualizar</button>
                </div>
                <!-- /.card-footer-->
            </div>
        </form>
    </div>
</div>