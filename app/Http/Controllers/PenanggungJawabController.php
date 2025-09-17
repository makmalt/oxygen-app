<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use Illuminate\Http\JsonResponse;

class PenanggungJawabController extends Controller
{
    public function index(): JsonResponse
    {
        $items = PenanggungJawab::orderBy('nama')->get(['id', 'nama']);
        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:penanggung_jawab,nama',
        ]);

        $nama = PenanggungJawab::create($validated);
        return response()->json($nama, 201);
    }
}
