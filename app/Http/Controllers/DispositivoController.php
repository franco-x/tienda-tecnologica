<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DispositivoController extends Controller
{
    public function index()
    {
        return Inertia::render('Dispositivos/Index', [
            'dispositivos' => Dispositivo::with('marca')->latest()->get(),
            'marcas' => Marca::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return redirect()->route('dispositivos.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('dispositivos', 'public');
        }

        Dispositivo::create([
            'marca_id' => $request->marca_id,
            'modelo' => $request->modelo,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'imagen' => $imagenPath,
        ]);

        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo registrado correctamente.');
    }

    public function show(Dispositivo $dispositivo)
    {
        return redirect()->route('dispositivos.index');
    }

    public function edit(Dispositivo $dispositivo)
    {
        return redirect()->route('dispositivos.index');
    }

    public function update(Request $request, Dispositivo $dispositivo)
    {
        $request->validate([
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagenPath = $dispositivo->imagen;

        if ($request->hasFile('imagen')) {
            if ($dispositivo->imagen && Storage::disk('public')->exists($dispositivo->imagen)) {
                Storage::disk('public')->delete($dispositivo->imagen);
            }

            $imagenPath = $request->file('imagen')->store('dispositivos', 'public');
        }

        $dispositivo->update([
            'marca_id' => $request->marca_id,
            'modelo' => $request->modelo,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'imagen' => $imagenPath,
        ]);

        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo actualizado correctamente.');
    }

    public function destroy(Dispositivo $dispositivo)
    {
        if ($dispositivo->imagen && Storage::disk('public')->exists($dispositivo->imagen)) {
            Storage::disk('public')->delete($dispositivo->imagen);
        }

        $dispositivo->delete();

        return redirect()->route('dispositivos.index')->with('success', 'Dispositivo eliminado correctamente.');
    }
}