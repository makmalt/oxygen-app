<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBotol;
use Illuminate\Http\JsonResponse;

class JenisBotolController extends Controller
{
    public function index(): JsonResponse
    {
        $items = JenisBotol::orderBy('nama_jenis')->get(['id', 'nama_jenis']);
        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:50|unique:jenis_botol,nama_jenis',
        ]);

        $jenis = JenisBotol::create($validated);
        return response()->json($jenis, 201);
    }
}
