<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Empleado;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with(['area', 'roles'])->latest()->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $areas = Area::orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        return view('empleados.form', ['empleado' => null, 'areas' => $areas, 'roles' => $roles]);
    }

    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'nombre'      => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:255'],
                'email'       => ['required', 'max:255', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/', 'unique:empleados,email'],
                'sexo'        => ['required', 'in:M,F'],
                'area_id'     => ['required', 'exists:areas,id'],
                'boletin'     => ['nullable', 'boolean'],
                'descripcion' => ['required', 'min:10'],
                'roles'       => ['required', 'array', 'min:1'],
                'roles.*'     => ['integer', 'exists:roles,id'],
            ], [
                'nombre.regex' => 'El nombre solo permite letras y espacios.',
                'email.regex'  => 'El correo no es válido.',
                'roles.required' => 'Selecciona al menos un rol.',
            ]);

            if ($v->fails()) return back()->withErrors($v)->withInput();

            $empleado = Empleado::create([
                'nombre'      => $request->string('nombre'),
                'email'       => $request->string('email'),
                'sexo'        => $request->string('sexo'),
                'area_id'     => $request->integer('area_id'),
                'boletin'     => $request->boolean('boletin'),
                'descripcion' => $request->string('descripcion'),
            ]);

            $empleado->roles()->sync($request->input('roles'));

            return redirect()->route('empleados.index')->with('status', 'Empleado creado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al crear empleado', ['e' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al crear el empleado.')->withInput();
        }
    }

    public function edit(Empleado $empleado)
    {
        $areas = Area::orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        $empleado->load('roles');
        return view('empleados.form', compact('empleado', 'areas', 'roles'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        try {
            $v = Validator::make($request->all(), [
                'nombre'      => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:255'],
                'email'       => ['required', 'max:255', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/', 'unique:empleados,email,' . $empleado->id],
                'sexo'        => ['required', 'in:M,F'],
                'area_id'     => ['required', 'exists:areas,id'],
                'boletin'     => ['nullable', 'boolean'],
                'descripcion' => ['required', 'min:10'],
                'roles'       => ['required', 'array', 'min:1'],
                'roles.*'     => ['integer', 'exists:roles,id'],
            ]);

            if ($v->fails()) return back()->withErrors($v)->withInput();

            $empleado->update([
                'nombre'      => $request->string('nombre'),
                'email'       => $request->string('email'),
                'sexo'        => $request->string('sexo'),
                'area_id'     => $request->integer('area_id'),
                'boletin'     => $request->boolean('boletin'),
                'descripcion' => $request->string('descripcion'),
            ]);

            $empleado->roles()->sync($request->input('roles'));

            return redirect()->route('empleados.index')->with('status', 'Empleado actualizado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar empleado', ['id' => $empleado->id, 'e' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al actualizar el empleado.')->withInput();
        }
    }

    public function destroy(Empleado $empleado)
    {
        try {
            $empleado->delete();
            return back()->with('status', 'Empleado eliminado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar empleado', ['id' => $empleado->id, 'e' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al eliminar el empleado.');
        }
    }
}
