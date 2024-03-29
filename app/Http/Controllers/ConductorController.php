<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestConductor;
use App\Http\Requests\RequestPersona;
use App\Models\Arl;
use App\Models\Empresa;
use App\Models\Eps;
use App\Models\MarcaVehiculo;
use App\Models\Persona;
use App\Models\PersonaVehiculo;
use App\Models\Registro;
use App\Models\TipoVehiculo;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ConductorController extends Controller
{
    protected $usuarios;
    protected $conductores;
    protected $eps;
    protected $arl;
    protected $vehiculos;
    protected $tipoVehiculos;
    protected $marcaVehiculos;
    protected $empresas;

    /**
     * Constructor que inicializa todos los modelos
     */
    public function __construct(User $usuarios, Persona $conductores, Eps $eps, Arl $arl, Vehiculo $vehiculos, TipoVehiculo $tipoVehiculos, MarcaVehiculo $marcaVehiculos, Empresa $empresas){
        $this->usuarios = $usuarios;
        $this->conductores = $conductores;
        $this->eps = $eps;
        $this->arl = $arl;
        $this->vehiculos = $vehiculos;
        $this->tipoVehiculos = $tipoVehiculos;
        $this->marcaVehiculos = $marcaVehiculos;
        $this->empresas = $empresas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exitCode = Artisan::call('cache:clear');
        $this->usuarios->asiganrRol(auth()->user());
        $eps = $this->eps->obtenerEps();
        $arl = $this->arl->obtenerArl();
        return view('pages.conductores.mostrar', compact('eps', 'arl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exitCode = Artisan::call('cache:clear');   
        $this->usuarios->asiganrRol(auth()->user());
        [$eps, $arl, $vehiculos, $tipoVehiculos, $marcaVehiculos, $empresas] = $this->obtenerModelos();
        return view('pages.conductores.crear', compact('eps', 'arl', 'vehiculos', 'tipoVehiculos', 'marcaVehiculos', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestConductor $request)
    {
        $nuevoConductor = $request->all();
        $nuevoConductor['nombre'] = ucwords(mb_strtolower($nuevoConductor['nombre']));
        $nuevoConductor['apellido'] = ucwords(mb_strtolower($nuevoConductor['apellido']));
        $nuevoConductor['colaborador'] = ucwords(mb_strtolower($nuevoConductor['colaborador']));
        $nuevoConductor['descripcion'] = ucfirst(mb_strtolower($nuevoConductor['descripcion']));
        $nuevoConductor['identificador'] = strtoupper($nuevoConductor['identificador']);
        $nuevoConductor['id_usuario'] = auth()->user()->id_usuarios;

        if(!isset($nuevoConductor['foto'])){ //saber si es null
            $url = null;
        } else{
            $img = $request->foto;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $fotoDecodificada = base64_decode($img);
            $filename = 'conductores/'. $nuevoConductor['identificacion']. '_'. date('Y-m-d'). '.png';
            $foto = Image::make($fotoDecodificada)->resize(600, 500);
            Storage::put('public/' . $filename, $foto->encode());
            $url = Str::replaceFirst('/', '', Storage::url($filename));
        } 

        //Crear registro de nuevo conductor dato a dato con la información del request
        $conductor = Persona::create([
            'id_usuario' => $nuevoConductor['id_usuario'],
            'id_tipo_persona' => 4,
            'nombre' => $nuevoConductor['nombre'],
            'apellido' => $nuevoConductor['apellido'],
            'identificacion' => $nuevoConductor['identificacion'],
            'id_eps' => $nuevoConductor['id_eps'],
            'id_arl' => $nuevoConductor['id_arl'],
            'foto' => $url,
            'tel_contacto' => $nuevoConductor['tel_contacto'],
        ]);
        $conductor->save();

        if(isset($nuevoConductor['id_vehiculo'])){ 
            PersonaVehiculo::create([
                'id_vehiculo' => $nuevoConductor['id_vehiculo'],
                'id_persona' => $conductor->id_personas, 
            ])->save();
            $this->store3($nuevoConductor, $conductor->id_personas, $nuevoConductor['id_vehiculo']);
            $modal = [$conductor->nombre.' '.$conductor->apellido, $this->vehiculos->obtenerVehiculo($nuevoConductor['id_vehiculo'])->identificador, 'asignado'];
        } else {
            [$mensajeVehiculo, $id_vehiculo] = $this->store2($nuevoConductor, $conductor->id_personas);
            $this->store3($nuevoConductor, $conductor->id_personas, $id_vehiculo);
            $modal = [$conductor->nombre.' '.$conductor->apellido, $mensajeVehiculo, 'creado'];
        }

        return redirect()->action([ConductorController::class, 'create'])->with('crear_conductor', $modal);
    }

    /**
     * Función que permite registrar un nuevo vehículo creado desde el módulo de conductores
     */
    public function store2($datos, $id_persona)
    {
        if(!isset($datos['foto_vehiculo'])){ //saber si es null
            $url = null;
        } else {
            $img = $datos['foto_vehiculo'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $fotoDecodificada = base64_decode($img);
            $filename = 'vehiculos/'. $id_persona. '_'. $datos['identificador']. '_'.date('Y-m-d'). '.png';
            $foto = Image::make($fotoDecodificada)->resize(600, 500);
            Storage::put('public/' . $filename, $foto->encode());
            $url = Str::replaceFirst('/', '', Storage::url($filename));
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

    /**
     * Función que permite hacer un registro de la entrada de un conductor al momento que se crea un nuevo conductor en la base de datos
     */
    public function store3($datos, $id_persona, $id_vehiculo)
    {
        Registro::create([
            'id_persona' => $id_persona,
            'ingreso_persona' => date('Y-m-d H:i:s'),
            'ingreso_vehiculo' => date('Y-m-d H:i:s'),
            'id_vehiculo' => $id_vehiculo,
            'descripcion' => $datos['descripcion'],
            'empresa_visitada' => $datos['empresa_visitada'],
            'colaborador' => $datos['colaborador'],
            'ficha' => $datos['ficha'],
            'id_usuario' => $datos['id_usuario'],
        ])->save(); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestPersona $request, $id)
    {
        $conductor = $request->all();
        $conductor['nombre'] = ucwords(mb_strtolower($conductor['nombre']));
        $conductor['apellido'] = ucwords(mb_strtolower($conductor['apellido']));
        Persona::findOrFail($id)->update($conductor);
        return redirect()->action([ConductorController::class, 'index'])->with('editar_conductor', $conductor['nombre']." ".$conductor['apellido']);
    }

    /**
     * Función que permite traer la información de los modelos de la Eps, Arl, TipoVehiculo, MarcaVehiculo y Empresa
     */
    public function obtenerModelos()
    {
        $eps = $this->eps->obtenerEps();
        $arl = $this->arl->obtenerArl();
        $vehiculos = $this->vehiculos->informacionVehiculos();
        $tipoVehiculos = $this->tipoVehiculos->obtenerTipoVehiculos();
        $marcaVehiculos = $this->marcaVehiculos->obtenerMarcaVehiculos();
        $empresas = $this->empresas->obtenerEmpresas();

        return [$eps, $arl, $vehiculos, $tipoVehiculos, $marcaVehiculos, $empresas];
    }

    /**
     * Función que permite retornar en un fotmato JSON los datos de los conductores, arl y eps donde tengan un id en común.
     */
    public function informacionConductores()
    {
        return response()->json( $this->conductores ->informacionPersonas(4));      
    } 
}