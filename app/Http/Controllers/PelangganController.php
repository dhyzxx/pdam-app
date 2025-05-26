<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('nama_pelanggan', 'like', "%{$searchTerm}%")
                  ->orWhere('id_pelanggan', 'like', "%{$searchTerm}%");
        }

        $pelanggans = $query->orderBy('nama_pelanggan')->paginate(100);
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|unique:pelanggans|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['pemakaianAir' => function ($query) {
            $query->orderBy('bulan', 'desc');
        }, 'tagihan' => function ($query) {
            $query->orderBy('bulan_tagihan', 'desc');
        }]);
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'id_pelanggan' => 'required|string|max:255|unique:pelanggans,id_pelanggan,' . $pelanggan->id,
            'nama_pelanggan' => 'required|string|max:255',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Pelanggan berhasil dihapus.');
    }
}