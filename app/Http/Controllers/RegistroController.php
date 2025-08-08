<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function create(Request $request)
    {
        $registros = Registro::latest()->get();

        $registro = null;
        if ($request->has('id')) {
            $registro = Registro::findOrFail((int) $request->get('id'));
        }

        return view('form', compact('registros', 'registro'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre'      => ['required', 'min:3', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
                'descripcion' => ['required', 'min:10'],
                'opciones'    => ['required', 'in:1,2'],
                'genero'      => ['required', 'in:masculino,femenino'],
                'acepto'      => ['accepted'],
            ], [
                'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            Registro::create([
                'nombre'      => $request->string('nombre'),
                'descripcion' => $request->string('descripcion'),
                'opciones'    => $request->string('opciones'),
                'genero'      => $request->string('genero'),
                'acepto'      => $request->boolean('acepto'),
            ]);

            return back()->with('status', 'Registro creado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al crear registro', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al crear el registro.')->withInput();
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre'      => ['required', 'min:3', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
                'descripcion' => ['required', 'min:10'],
                'opciones'    => ['required', 'in:1,2'],
                'genero'      => ['required', 'in:masculino,femenino'],
                'acepto'      => ['accepted'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $registro = Registro::findOrFail($id);
            $registro->update([
                'nombre'      => $request->string('nombre'),
                'descripcion' => $request->string('descripcion'),
                'opciones'    => $request->string('opciones'),
                'genero'      => $request->string('genero'),
                'acepto'      => $request->boolean('acepto'),
            ]);

            return redirect()->route('registros.create', ['id' => $registro->id])
                ->with('status', "Registro #{$id} actualizado correctamente.");
        } catch (\Throwable $e) {
            Log::error('Error al actualizar registro', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al actualizar el registro.')->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            $registro = Registro::findOrFail($id);
            $registro->delete();

            return back()->with('status', "Registro #{$id} eliminado correctamente.");
        } catch (\Throwable $e) {
            Log::error('Error al eliminar registro', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Ha ocurrido un error al eliminar el registro.');
        }
    }
}
