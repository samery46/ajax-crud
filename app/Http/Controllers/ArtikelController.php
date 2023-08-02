<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\View\View;
//return type redirectResponse
use Illuminate\Http\RedirectResponse;
//import Facade "Storage"
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\Datatables;

class ArtikelController extends Controller
{
    //


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Artikel::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        /* $artikels = Artikel::latest()->paginate(5); */

        return view('artikels.index');
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

    public function edit(string $id): View
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //render view with artikel
        return view('artikels.edit', compact('artikel'));
    }
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/artikels', $image->hashName());

            //delete old image
            Storage::delete('public/artikels/'.$artikel->image);

            //update artikel with new image
            $artikel->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update artikel without image
            $artikel->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('artikels.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //delete image
        Storage::delete('public/artikels/'. $artikel->image);

        //delete artikel
        $artikel->delete();

        //redirect to index
        return redirect()->route('artikels.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

}
