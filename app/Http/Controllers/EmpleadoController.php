<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Empleado;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $perPage = 10; // Tamaño de página por defecto

        $version = Cache::get('empleados:index:version', 1);
        $key = "empleados:index:v{$version}:p{$page}:pp{$perPage}";

        $empleados = Cache::remember($key, 60 * 60 * 24, function () use ($perPage) {
            try {
                return Empleado::with(['area', 'roles'])
                    ->latest()
                    ->paginate($perPage)
                    ->withQueryString();
            } catch (\Throwable $e) {
                Log::warning('Fallo listando empleados, devolviendo paginador vacío (¿migraciones pendientes?)', ['e' => $e->getMessage()]);
                return new LengthAwarePaginator(collect(), 0, $perPage, 1, [
                    'path' => url()->current(),
                    'query' => request()->query(),
                ]);
            }
        });

        if ($empleados instanceof LengthAwarePaginator) {
            $empleados->setPath(url()->current());
        }

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
            // Tocar el modelo y aumentar versión de caché para invalidar listados
            $empleado->touch();
            $this->bumpEmpleadosCacheVersion();

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
            // Tocar el modelo y aumentar versión de caché para invalidar listados
            $empleado->touch();
            $this->bumpEmpleadosCacheVersion();

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
            // Invalidar caché del listado
            $this->bumpEmpleadosCacheVersion();
            return back()->with('status', 'Empleado eliminado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar empleado', ['id' => $empleado->id, 'e' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al eliminar el empleado.');
        }
    }

    private function bumpEmpleadosCacheVersion(): void
    {
        try {
            $v = Cache::increment('empleados:index:version');
            if (!$v) {
                // Si no existe la clave, establece una versión nueva (distinta a la usada por defecto)
                Cache::forever('empleados:index:version', (string) now()->timestamp);
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo incrementar la versión de la caché de empleados', ['e' => $e->getMessage()]);
        }
    }
}
