<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Tambah Transaksi
    public function store(Request $request)
    {
        $request->validate([
            'nama_transaksi' => 'required|string',
            'jenis' => 'required|in:pemasukan,pengeluaran',
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
            'jenis' => 'required|in:pemasukan,pengeluaran',
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
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
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
            'message' => 'Transaksi berhasil diubah',
            'data' => $transaksi
        ]);
    }

    // Hapus Transaksi
    public function destroy($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaksi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }

    // List Transaksi
    public function index(Request $request)
    {
        $query = Transaksi::where('user_id', $request->user()->id);

        // Jenis transaksi (pemasukan / pengeluaran)
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
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

    // Transaksi tampil di Dashboard
    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;

        $totalPemasukan = Transaksi::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->sum('total');

        $totalPengeluaran = Transaksi::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->sum('total');

        $transaksiTerbaru = Transaksi::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'saldo' => $totalPemasukan - $totalPengeluaran,
                'transaksi_terbaru' => $transaksiTerbaru
            ]
        ]);
    }

    // Transaksi Rekap Keuangan
    public function rekap(Request $request)
    {
        $userId = auth()->id();

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // Total Pemasukan
        $totalPemasukan = Transaksi::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('total');

        // Total Pengeluaran
        $totalPengeluaran = Transaksi::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('total');

        // Per kategori pengeluaran
        $rekapKategori = Transaksi::select(
            'kategori',
            DB::raw('SUM(total) as total')
        )
        ->where('user_id', $userId)
        ->where('jenis', 'pengeluaran')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->groupBy('kategori')
        ->orderByDesc('total')
        ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'rekap_kategori' => $rekapKategori
            ]
        ]);
    }
}
