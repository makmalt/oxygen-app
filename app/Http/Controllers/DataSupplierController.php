<?php

namespace App\Http\Controllers;

use App\Models\DataSupplier;
use Illuminate\Http\Request;

class DataSupplierController extends Controller
{
    public function index()
    {
        $suppliers = DataSupplier::orderBy('nama_supplier')->get();
        return view('data_supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('data_supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:100|unique:data_supplier,nama_supplier',
        ]);

        DataSupplier::create($validated);

        return redirect()->route('data_supplier.index')->with('success', 'Supplier ditambahkan');
    }
}
