<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCalificacione;

class PruebaController extends Controller
{
    public function store(Request $req)
    {
      /*
      1. Acción POST:
      El webservice debera de dar de alta una calificación para el alumno en la tabla de t_calificaciones.
      Si la alta es exitosa el servicio web debera responder en formato json:
      {"success":"ok", "msg":"calificacion registrada"}
      */

      $reglas = [
            'id_t_materias' => 'required|integer|exists:t_materias,id_t_materias',
            'id_t_usuarios' => 'required|integer|exists:t_alumnos,id_t_usuarios',
            'calificacion'  => 'required|numeric|min:0|max:10'
            ];

      try {
        $validar = \Validator::make($req->all(), $reglas);

        if ($validar->fails()) {
            return [
                'success' => 'error',
                'msg'  => $validar->errors()->all()
            ];
        }

        TCalificacione::create([
          'id_t_materias'=>$req->id_t_materias,
          'id_t_usuarios'=>$req->id_t_usuarios,
          'calificacion'=>$req->calificacion,
          'fecha_registro'=>date('Y-m-d')
        ]);

        return ["success"=>"ok", "msg"=>"calificacion registrada"];

      } catch (\Exception $e) {
        return \Response::json(['success' => 'error'], 500);
      }
    }

    public function show($id)
    {
      /*
      2. Acción GET:
      El webservice debera de recibir la variable de id del alumno y debera devolver el listado de las
      calificaciones en en formato json, adicional tendra que enviar el promedio de las calificaciones del
      alumno.

      Ejemplo de la respuesta que tendra que devolver el webservice.
      [{id_t_usuario:1,nombre:”John”,”apellido”:”Dow”,”materia”,”programacion I”,”calificacion”:10,”fecha_registro”:”10/10/2017”},
      {id_t_usuario:1,nombre:”John”,”apellido”:”Dow”,”materia”,”ingenieria de software”,”calificacion”:8.5,”fecha_registro”:”10/10/2017”},{“promedio”:9.25}]

      *La fecha debera devolverla en el formato dd/mm/yyyy
      */
      try {
        $calificaciones = TCalificacione::select(\DB::raw("
            t_alumnos.id_t_usuarios, t_alumnos.nombre as nombre, ap_paterno as apellido,
            t_materias.nombre as materia, calificacion, DATE_FORMAT(fecha_registro,'%d/%m/%Y') as fecha_registro
            "))
          ->where('t_calificaciones.id_t_usuarios',$id)
          ->join('escuela.t_alumnos', 't_alumnos.id_t_usuarios', '=', 't_calificaciones.id_t_usuarios')
          ->join('escuela.t_materias', 't_materias.id_t_materias', '=', 't_calificaciones.id_t_materias')
          ->get();

        $promedio['promedio'] = TCalificacione::where('id_t_usuarios',$id)
              ->avg('calificacion');

        $res=$calificaciones->toArray();
        array_push($res,$promedio);

        return $res ;

      } catch (Exception $e) {
        return \Response::json(['success' => 'error'], 500);
      }
    }

    public function update(Request $req)
    {
      /*
      3. Accion PUT:
      Actualizar una calificación de la tabla de t_calificaciones
      Si la alta es exitosa el servicio web debera responder en formato json:
      {"success":"ok", "msg":"calificacion actualizada”}
      */
      $reglas = [
            'id_t_calificaciones' => 'required|integer|exists:t_calificaciones,id_t_calificaciones',
            'calificacion'        => 'required|numeric|min:0|max:10'
            ];
      try {

        $validar = \Validator::make($req->all(), $reglas);

        if ($validar->fails()) {
            return [
                'success' => 'error',
                'msg'  => $validar->errors()->all()
            ];
        }

        $calificacion=TCalificacione::where('id_t_calificaciones',$req->id_t_calificaciones);
        $calificacion->update(['calificacion'=>$req->calificacion]);

        return ["success"=>"ok", "msg"=>"calificacion actualizada"];

      } catch (Exception $e) {
        return \Response::json(['success' => 'error'], 500);
      }
    }

    public function destroy(Request $req)
    {
      /*
      4. Accion DELETE:
      Realizar un webservice DELETE eliminar fisicamente el registro de una calificacion. Si el registro se
      elimina con exito el webservice debera devolver la siguiente respuesta.
      {"success":"ok", "msg":"calificacion eliminada”}
      */

      #Metodo de seguridad básica para el metodo delete
      $passwd = $req->headers->get('password');
      if (isset($passwd) != env('KEY')) {
            return \Response::json(['success' => 'Acceso no autorizado'], 401);
        }
      ##################

      try {

        $calificacion=TCalificacione::where('id_t_calificaciones',$req->id_t_calificaciones)->delete();
        return ["success"=>"ok", "msg"=>"calificacion eliminada"];

      } catch (Exception $e) {
        return \Response::json(['success' => 'error'], 500);
      }
    }
}
