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
}
