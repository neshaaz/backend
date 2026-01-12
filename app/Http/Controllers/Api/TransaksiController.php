<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // List Transaksi
    public function index(Request $request)
    {
        $query = Transaksi::where('user_id', $request->user()->id);

        // Jenis transaksi (pemasukan / pengeluaran)
        if ($request->jenis) {
            $query->where('type', $request->jenis);
        }

        // Jenis kategori
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $transaksi = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $transaksi
        ]);
    }

    // Tambah Transaksi
    public function store(Request $request)
    {
        $request->validate([
            'nama_transaksi' => 'required|string',
            'jenis' => 'required|in:income,expense',
            'kategori' => 'required|string',
            'total' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $transaksi = Transaksi::create([
            'user_id' => $request->user()->id,
            'nama_transaksi' => $request->nama_transaksi,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'total' => $request->total,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $transaksi
        ], 201);
    }

    // Edit Transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_transaksi' => 'required|string',
            'jenis' => 'required|in:income,expense',
            'kategori' => 'required|string',
            'total' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'massage' => 'Transaksi tidak ditemukan'
            ], 400);
        }

        $transaksi->update([
            'nama_transaksi' => $request->nama_transaksi,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'total' => $request->total,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'status' => true,
            'massage' => 'Transaksi berhasil diubah',
            'data' => $transaksi
        ]);
    }
}
