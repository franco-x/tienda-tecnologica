<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarcaController extends Controller
{
    public function index()
    {
        return Inertia::render('Marcas/Index', [
            'marcas' => Marca::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
        ]);

        Marca::create([
            'nombre' => $request->nombre,
            'pais' => $request->pais,
        ]);

        return redirect()->route('marcas.index');
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
        ]);

        $marca->update([
            'nombre' => $request->nombre,
            'pais' => $request->pais,
        ]);

        return redirect()->route('marcas.index');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index');
    }
}