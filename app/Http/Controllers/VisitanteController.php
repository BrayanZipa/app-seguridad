<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPersona;
use App\Models\Activo;
use App\Models\Arl;
use App\Models\Empresa;
use App\Models\Eps;
use App\Models\MarcaVehiculo;
use App\Models\Persona;
use App\Models\PersonaVehiculo;
use App\Models\Registro;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VisitanteController extends Controller
{
    protected $visitantes;
    protected $eps;
    protected $arl;
    protected $tipoVehiculos;
    protected $marcaVehiculos;
    protected $empresas;
    protected $activos;

    /**
     * Contructor que inicializa todos los modelos
     */
    public function __construct(Persona $visitantes, Eps $eps, Arl $arl, TipoVehiculo $tipoVehiculos, MarcaVehiculo $marcaVehiculos, Empresa $empresas, Activo $activos)
    {
        $this->visitantes = $visitantes;
        $this->eps = $eps;
        $this->arl = $arl;
        $this->tipoVehiculos = $tipoVehiculos;
        $this->marcaVehiculos = $marcaVehiculos;
        $this->empresas = $empresas;
        $this->activos = $activos;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exitCode = Artisan::call('cache:clear');
        $eps = $this->eps->obtenerEps();
        $arl = $this->arl->obtenerArl();
        return view('pages.visitantes.mostrar', compact('eps', 'arl'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exitCode = Artisan::call('cache:clear');
        [$eps, $arl, $tipoVehiculos, $marcaVehiculos, $empresas] = $this->obtenerModelos();
        return view('pages.visitantes.crear', compact('eps', 'arl', 'tipoVehiculos', 'marcaVehiculos', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestPersona $request)
    {
        $nuevoVisitante = $request->all();

        if($nuevoVisitante['casoIngreso'] == 'casoVehiculo'){
            $this->validarVehiculo($request);

        } else if ($nuevoVisitante['casoIngreso'] == 'casoActivo'){
            $this->validarActivo($request);

        } else if ($nuevoVisitante['casoIngreso'] == 'casoVehiculoActivo'){
            $this->validarVehiculo($request);
            $this->validarActivo($request);
        }

        $nuevoVisitante['nombre'] = ucwords(mb_strtolower($nuevoVisitante['nombre']));
        $nuevoVisitante['apellido'] = ucwords(mb_strtolower($nuevoVisitante['apellido']));
        $nuevoVisitante['colaborador'] = ucwords(mb_strtolower($nuevoVisitante['colaborador']));
        $nuevoVisitante['descripcion'] = ucfirst(mb_strtolower($nuevoVisitante['descripcion']));
        $nuevoVisitante['identificador'] = strtoupper($nuevoVisitante['identificador']);
        $nuevoVisitante['activo'] = ucwords(mb_strtolower($nuevoVisitante['activo']));
        $nuevoVisitante['codigo'] = ucfirst($nuevoVisitante['codigo']);
        $nuevoVisitante['id_usuario'] = auth()->user()->id_usuarios;

        if(!isset($nuevoVisitante['id_eps'])){ //saber si existe
            $nuevoVisitante['id_eps'] = null;
        } if (!isset($nuevoVisitante['id_arl'])){ //saber si existe
            $nuevoVisitante['id_arl'] = null;
        }
        
        if(!isset($nuevoVisitante['foto'])){ //saber si es null
            $url = null;
        } else{
            $img = $request->foto;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $foto = base64_decode($img);
            $filename = 'visitantes/'. $nuevoVisitante['identificacion']. '_'. date('Y-m-d'). '.png';
            $ruta = storage_path() . '\app\public/' .  $filename;
            Image::make($foto)->resize(600, 500)->save($ruta);
            $url = Storage::url($filename);
        }      

        //Crear registro de nuevo visitante dato a dato con la informaci??n del request
        $visitante = Persona::create([
            'id_usuario' => $nuevoVisitante['id_usuario'],
            'id_tipo_persona' => 1,
            'nombre' => $nuevoVisitante['nombre'],
            'apellido' => $nuevoVisitante['apellido'],
            'identificacion' => $nuevoVisitante['identificacion'],
            'id_eps' => $nuevoVisitante['id_eps'],
            'id_arl' => $nuevoVisitante['id_arl'],
            'foto' => $url,
            'tel_contacto' => $nuevoVisitante['tel_contacto'],
        ]);
        $visitante->save();

        //Ingreso de datos dependiendo de que formularios fueron ingresados
        if($nuevoVisitante['casoIngreso'] == 'casoVehiculo'){
            [$mensajeVehiculo, $id_vehiculo] = $this->store2($nuevoVisitante, $visitante->id_personas);
            $this->store4($nuevoVisitante, $visitante->id_personas, $id_vehiculo, null);
            $modal = [$visitante->nombre.' '.$visitante->apellido, $mensajeVehiculo];
            return redirect()->action([VisitanteController::class, 'create'])->with('crear_visitante_vehiculo', $modal);

        } else if($nuevoVisitante['casoIngreso'] == 'casoActivo'){
            $mensajeActivo = $this->store3($nuevoVisitante, $visitante->id_personas);
            $this->store4($nuevoVisitante, $visitante->id_personas, null, $mensajeActivo);
            $modal = [$visitante->nombre.' '.$visitante->apellido, $mensajeActivo];
            return redirect()->action([VisitanteController::class, 'create'])->with('crear_visitante_activo', $modal);
            
        } else if($nuevoVisitante['casoIngreso'] == 'casoVehiculoActivo'){
            [$mensajeVehiculo, $id_vehiculo] = $this->store2($nuevoVisitante, $visitante->id_personas);
            $mensajeActivo = $this->store3($nuevoVisitante, $visitante->id_personas);
            $this->store4($nuevoVisitante, $visitante->id_personas, $id_vehiculo, $mensajeActivo);
            $modal = [$visitante->nombre.' '.$visitante->apellido, $mensajeVehiculo, $mensajeActivo];
            return redirect()->action([VisitanteController::class, 'create'])->with('crear_visitante_vehiculoActivo', $modal);
            
        } else {
            $this->store4($nuevoVisitante, $visitante->id_personas, null, null);
            return redirect()->action([VisitanteController::class, 'create'])->with('crear_visitante', $visitante->nombre.' '.$visitante->apellido);
        }   
    }

    //Funci??n que permite registrar un nuevo veh??culo creado desde el modulo de visitantes
    public function store2($datos, $id_persona)
    {
        if(!isset($datos['foto_vehiculo'])){ //saber si es null
            $url = null;
        } else {
            $img = $datos['foto_vehiculo'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $foto = base64_decode($img);
            $filename = 'vehiculos/'. $id_persona. '_'. $datos['identificador']. '_'.date('Y-m-d'). '.png';
            $ruta = storage_path() . '\app\public/' .  $filename;
            Image::make($foto)->resize(600, 500)->save($ruta);
            $url = Storage::url($filename);
        }

        if(!isset($datos['id_marca_vehiculo'])){ //saber si existe
            $datos['id_marca_vehiculo'] = null;
        }

        $vehiculo = Vehiculo::create([
            'identificador' => $datos['identificador'],
            'id_tipo_vehiculo' => $datos['id_tipo_vehiculo'],
            'id_marca_vehiculo' => $datos['id_marca_vehiculo'],
            'foto_vehiculo' => $url,
            'id_usuario' => $datos['id_usuario'],
        ]);
        $vehiculo->save();

        PersonaVehiculo::create([
            'id_vehiculo' => $vehiculo->id_vehiculos,
            'id_persona' => $id_persona,
        ])->save();

        return [$vehiculo->identificador, $vehiculo->id_vehiculos];
    }

    //Funci??n que permite registrar un nuevo activo creado desde el modulo de visitantes
    public function store3($datos, $id_persona)
    {
        $activo = Activo::create([
            'activo' => $datos['activo'],
            'codigo' => $datos['codigo'],
            'id_usuario' => $datos['id_usuario'],
            'id_persona' => $id_persona,
        ]);
        $activo->save();
        return $activo->codigo;
    }

    //Funci??n que permite hacer un registro de la entrada de un visitante al momento que se crea un nuevo visitante en la base de datos
    public function store4($datos, $id_persona, $id_vehiculo, $activo)
    {
        if($datos['casoIngreso'] == 'casoVehiculo'){
            Registro::create([
                'id_persona' => $id_persona,
                'ingreso_persona' => date('Y-m-d H:i:s'),
                'ingreso_vehiculo' => date('Y-m-d H:i:s'),
                'id_vehiculo' => $id_vehiculo,
                'descripcion' => $datos['descripcion'],
                'empresa_visitada' => $datos['empresa_visitada'],
                'colaborador' => $datos['colaborador'],
                'id_usuario' => $datos['id_usuario'],
            ])->save(); 

        } else if ($datos['casoIngreso'] == 'casoActivo'){
            Registro::create([
                'id_persona' => $id_persona,
                'ingreso_persona' => date('Y-m-d H:i:s'),
                'ingreso_activo' => date('Y-m-d H:i:s'),
                'codigo_activo' => $activo,
                'descripcion' => $datos['descripcion'],
                'empresa_visitada' => $datos['empresa_visitada'],
                'colaborador' => $datos['colaborador'],
                'id_usuario' => $datos['id_usuario'],
            ])->save(); 

        } else if ($datos['casoIngreso'] == 'casoVehiculoActivo'){
            Registro::create([
                'id_persona' => $id_persona,
                'ingreso_persona' => date('Y-m-d H:i:s'),
                'ingreso_vehiculo' => date('Y-m-d H:i:s'),
                'id_vehiculo' => $id_vehiculo,
                'ingreso_activo' => date('Y-m-d H:i:s'),
                'codigo_activo' => $activo,
                'descripcion' => $datos['descripcion'],
                'empresa_visitada' => $datos['empresa_visitada'],
                'colaborador' => $datos['colaborador'],
                'id_usuario' => $datos['id_usuario'],
                ])->save();  

        } else {
            Registro::create([
                'id_persona' => $id_persona,
                'ingreso_persona' => date('Y-m-d H:i:s'),
                'descripcion' => $datos['descripcion'],
                'empresa_visitada' => $datos['empresa_visitada'],
                'colaborador' => $datos['colaborador'],
                'id_usuario' => $datos['id_usuario'],
            ])->save(); 
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestPersona $request, $id)
    {
        $visitante = $request->all();    
        $visitante['nombre'] = ucwords(mb_strtolower($visitante['nombre']));
        $visitante['apellido'] = ucwords(mb_strtolower($visitante['apellido']));
        Persona::findOrFail($id)->update($visitante);

        if(isset($visitante['codigo'])){ 
            $visitante['codigo'] = ucfirst($visitante['codigo']);
            if(isset($visitante['activo'])){
                $visitante['activo'] = ucwords(mb_strtolower($visitante['activo']));
            } else {
                $visitante['activo'] = 'Computador';
            }

            if(!$this->activos->existeActivo($visitante['codigo'], $id)){    
                Activo::updateOrCreate(
                    ['id_persona' => $id],
                    [
                        'activo' => $visitante['activo'],
                        'codigo' => $visitante['codigo'],
                        'id_usuario' => auth()->user()->id_usuarios,
                    ]
                );
            }         
        }
        return redirect()->action([VisitanteController::class, 'index'])->with('editar_visitante', $visitante['nombre']." ".$visitante['apellido']);
    }

    /**
     * Funci??n que permite traer la informaci??n de los modelos de la Eps, Arl, TipoVehiculo y MarcaVehiculo
     */
    public function obtenerModelos()
    {
        $eps = $this->eps->obtenerEps();
        $arl = $this->arl->obtenerArl();
        $tipoVehiculos = $this->tipoVehiculos->obtenerTipoVehiculos();
        $marcaVehiculos = $this->marcaVehiculos->obtenerMarcaVehiculos();
        $empresas = $this->empresas->obtenerEmpresas();

        return [$eps, $arl, $tipoVehiculos, $marcaVehiculos, $empresas];
    }

    /**
     * Funci??n que permite retornar en un formato JSON los datos de los visitantes, arl y eps donde tengan un id en com??n.
     */
    public function informacionVisitantes()
    {
        return response()->json($this->visitantes->informacionPersonas(1));      
    }

    /**
     * Funci??n que permite validar los datos ingresados en el formulario de veh??culo.
     */
    public function validarVehiculo(Request $request){
        $this->validate($request, [
            'identificador' => 'required|string|unique:se_vehiculos,identificador|alpha_num|max:15|min:6',
            'id_tipo_vehiculo' => 'required|integer',   
            'id_marca_vehiculo' => 'integer|nullable',
            'foto_vehiculo'  => 'required|string',
        ],[
            'identificador.required' => 'Se requiere que ingrese el n??mero identificador del veh??culo',
            'identificador.string' => 'El n??mero identificador debe ser de tipo texto',
            'identificador.unique' => 'No puede haber dos veh??culos con el mismo n??mero identificador',
            'identificador.alpha_num' => 'El identificador solo debe contener valores alfanum??ricos y sin espacios',
            'identificador.max' => 'El identificador del veh??culo no puede tener m??s de 15 caracteres',
            'identificador.min' => 'El identificador del veh??culo no puede tener menos de 6 caracteres',

            'id_tipo_vehiculo.required' => 'Se requiere que elija una opci??n en el tipo de veh??culo',
            'id_tipo_vehiculo.integer' => 'El tipo de veh??culo debe ser de tipo entero',

            'id_marca_vehiculo.integer' => 'La marca ded veh??culo debe ser de tipo entero',

            'foto_vehiculo.required' => 'Se requiere que tome una foto del veh??culo',
            'foto_vehiculo.string' => 'La informaci??n de la foto del veh??culo debe estar en formato de texto',
        ]);
    }
    
    /**
     * Funci??n que permite validar los datos ingresados en el formulario de activo.
     */
    public function validarActivo(Request $request){
        $this->validate($request, [
            'activo' => 'required|string|alpha|max:20|min:3',
            'codigo' => 'required|string|alpha_num|unique:se_activos,codigo|max:5|min:4',   
        ],[
            'activo.required' => 'Se requiere que ingrese el nombre del activo',
            'activo.string' => 'El nombre del activo debe ser de tipo texto',
            'activo.alpha' => 'El nombre del activo solo debe contener valores alfab??ticos y sin espacios',
            'activo.max' => 'El nombre del activo no puede tener m??s de 20 caracteres',
            'activo.min' => 'El nombre del activo no puede tener menos de 3 caracteres',

            'codigo.required' => 'Se requiere que ingrese el c??digo del activo',
            'codigo.string' => 'El c??digo del activo debe ser de tipo texto',
            'codigo.alpha_num' => 'El c??digo del activo solo debe contener valores alfanum??ricos y sin espacios',
            'codigo.unique' => 'No puede haber m??s de un activo con el mismo c??digo',
            'codigo.max' => 'El c??digo del activo no puede tener m??s de 5 caracteres',
            'codigo.min' => 'El c??digo del activo no puede tener menos de 4 caracteres',
        ]);
    }
}