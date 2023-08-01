<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    //
    public function index(): View
    {

        $artikels = Artikel::latest()->paginate(5);

        return view('artikels.index', compact('artikels'));
    }
}
