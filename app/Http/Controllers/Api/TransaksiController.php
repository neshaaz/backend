<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index() {
    return Transaction::all();
    }

    public function store(Request $request) {
        return Transaction::create($request->all());
    }
}
