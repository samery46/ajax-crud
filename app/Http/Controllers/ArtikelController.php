<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\View\View;
//return type redirectResponse
use Illuminate\Http\RedirectResponse;

class ArtikelController extends Controller
{
    //
    public function index(): View
    {

        $artikels = Artikel::latest()->paginate(5);

        return view('artikels.index', compact('artikels'));
    }

    public function create(): View
    {
        return view('artikels.create');
    }
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/artikels', $image->hashName());

        //create artikel
        Artikel::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('artikels.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //render view with artikel
        return view('artikels.show', compact('artikel'));
    }

}
