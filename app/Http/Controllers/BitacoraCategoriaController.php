<?php
// Archivo: app/Http/Controllers/BitacoraCategoriaController.php

namespace App\Http\Controllers;

use App\Models\BitacoraCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BitacoraCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categorias = BitacoraCategoria::where('activo', true)->get();
        return response()->json($categorias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50|unique:bitacora_categorias',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $categoria = BitacoraCategoria::create($request->all());
        return response()->json($categoria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BitacoraCategoria $categoria)
    {
        return response()->json($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BitacoraCategoria $categoria)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'string|max:50|unique:bitacora_categorias,codigo,' . $categoria->id,
            'nombre' => 'string|max:100',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $categoria->update($request->all());
        return response()->json($categoria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BitacoraCategoria $categoria)
    {
        // Soft delete (marcar como inactivo)
        $categoria->update(['activo' => false]);
        return response()->json(null, 204);
    }
}