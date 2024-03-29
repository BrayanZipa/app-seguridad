<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestConductor extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(){
        $this->merge(['identificacion' => (int)$this->input('identificacion')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $datos = $this->all();
        if(isset($datos['id_vehiculo'])){
            $validacion = $this->validacionConductor();
            $validacion['id_vehiculo'] = 'required|integer';   
        } else{
            $validacion = array_merge($this->validacionConductor(), $this->validacionVehiculo());
        }

        return $validacion;
    }

    public function messages()
    {
        return [
            'nombre.required' => 'Se requiere que ingrese el nombre',
            'nombre.string' => 'El nombre debe ser de tipo texto',
            'nombre.regex' => 'El nombre solo debe contener valores alfabéticos',
            'nombre.max' => 'El nombre no puede tener más de 25 caracteres',
            'nombre.min' => 'El nombre no puede tener menos de 3 caracteres',

            'apellido.required' => 'Se requiere que ingrese el apellido',
            'apellido.string' => 'El apellido debe ser de tipo texto',
            'apellido.regex' => 'El apellido solo debe contener valores alfabéticos',
            'apellido.max' => 'El apellido no puede tener más de 25 caracteres',
            'apellido.min' => 'El apellido no puede tener menos de 3 caracteres',

            'identificacion.required' => 'Se requiere que ingrese la identificación',
            'identificacion.numeric' => 'La identificación debe ser un valor númerico y no debe contener espacios',
            'identificacion.unique' => 'No puede haber dos personas con el mismo número de identificación',
            'identificacion.digits_between' => 'La identificación debe estar en un rago de 4 a 15 caracteres',

            'tel_contacto.required' => 'Se requiere que ingrese el teléfono',
            'tel_contacto.numeric' => 'El teléfono debe ser un valor númerico y no debe contener espacios',
            // 'tel_contacto.unique' => 'No puede haber dos personas con el mismo teléfono',
            'tel_contacto.digits_between' => 'El teléfono debe tener 7 o 10 caracteres',

            'id_eps.required' => 'Se requiere que elija una opción en la EPS',
            'id_eps.integer' => 'La EPS debe ser de tipo entero',

            'id_arl.required' => 'Se requiere que elija una opción en la ARL',
            'id_arl.integer' => 'La ARL debe ser de tipo entero',

            'empresa_visitada.required' => 'Se requiere que elija una opción en la empresa',
            'empresa_visitada.integer' => 'La Empresa debe ser de tipo entero',

            'colaborador.required' => 'Se requiere que ingrese al colaborador a cargo',
            'colaborador.string' => 'El colaborador debe ser de tipo texto',
            'colaborador.regex' => 'El colaborador solo debe contener valores alfabéticos',
            'colaborador.max' => 'El colaborador no puede tener más de 50 caracteres',
            'colaborador.min' => 'El colaborador no puede tener menos de 3 caracteres',

            'ficha.required' => 'Se requiere que ingrese la ficha',
            'ficha.numeric' => 'La ficha debe ser un valor númerico y no debe contener espacios',
            'ficha.digits_between' => 'La ficha debe tener máximo 2 dígitos',

            'descripcion.max' => 'La descripción solo puede tener un máximo de 255 caracteres',   

            'foto.required' => 'Se requiere que tome una foto de la persona',
            'foto.string' => 'La información de la foto debe estar en formato de texto',

            'identificador.required' => 'Se requiere que ingrese el identificador del vehículo',
            'identificador.string' => 'El identificador debe ser de tipo texto',
            'identificador.unique' => 'No puede haber dos vehículos con el mismo número identificador',
            'identificador.alpha_num' => 'El identificador solo debe contener valores alfanuméricos y sin espacios',
            'identificador.max' => 'El identificador del vehículo no puede tener más de 15 caracteres',
            'identificador.min' => 'El identificador del vehículo no puede tener menos de 6 caracteres',

            'id_tipo_vehiculo.required' => 'Se requiere que elija una opción en el tipo de vehículo',
            'id_tipo_vehiculo.integer' => 'El tipo de vehículo debe ser de tipo entero',

            'id_marca_vehiculo.integer' => 'La marca de vehículo debe ser de tipo entero',

            'foto_vehiculo.required' => 'Se requiere que tome una foto del vehículo',
            'foto_vehiculo.string' => 'La información de la foto del vehículo debe estar en formato de texto',

            'id_vehiculo.required' => 'Se requiere que elija una opción en el vehículo',
            'id_vehiculo.integer' => 'El vehículo debe ser de tipo entero',
        ];
    }

    /**
     * Función que retorna las validaciones que verifican los datos del conductor
     */
    public function validacionConductor()
    {
        return[
            'nombre' => 'required|string|regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u|max:25|min:3',
            'apellido' => 'required|string|regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u|max:25|min:3',   
            'identificacion' => 'required|numeric|unique:se_personas,identificacion,'.$this->id.',id_personas|digits_between:4,15',     
            'tel_contacto' => 'required|numeric|digits_between:7,10',   
            //|unique:se_personas,tel_contacto,'.$this->id.',id_personas
            'id_eps' => 'required|integer',         
            'id_arl' => 'required|integer',
            'empresa_visitada' => 'required|integer',
            'colaborador' => 'required|string|regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u|max:50|min:3',
            'ficha' => 'required|numeric|digits_between:1,2',
            'descripcion' => 'nullable|max:255',
            'foto' => 'required|string',
        ];
    } 

    /**
     * Función que retorna las validaciones que verifican los datos del vehículo perteneciente al conductor
     */
    public function validacionVehiculo()
    {
        return[
            'identificador' => 'required|string|unique:se_vehiculos,identificador|alpha_num|max:15|min:6',
            'id_tipo_vehiculo' => 'required|integer',   
            'id_marca_vehiculo' => 'integer|nullable',
            'foto_vehiculo'  => 'required|string',
        ];
    } 
}