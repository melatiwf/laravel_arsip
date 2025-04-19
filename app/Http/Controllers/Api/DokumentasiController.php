<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Dokumentasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//import Resource "PostResource"
use App\Http\Resources\DokumentasiResource;

//import Facade "Storage"
use Illuminate\Support\Facades\Storage;

//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class DokumentasiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts


        $dokumentasis = Dokumentasi::latest()->paginate(5);

        //return collection of posts as a resource
        return response()->json([
            'success' => true,
            'message' => 'List Data Dokumentasi',
            'data'    => $dokumentasis
        ]);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul'     => 'required',
            'deskripsi'     => 'required',
            'tanggal'     => 'required',

        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/dokumentasis', $image->hashName());

        //create post
        $dokumentasi = Dokumentasi::create([
            'image'     => $image->hashName(),
            'judul'     => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'tanggal'     => $request->tanggal,
           
        ]);

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Dokumentasi Berhasil Ditambahkan!',
            'data'    => $dokumentasi
        ]);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show($id)
    {
        //find post by ID
        $dokumentasi = Dokumentasi::find($id);

        //return single post as a resource
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Dokumentasi!',
            'data'    => $dokumentasi
        ]);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul'     => 'required',
            'deskripsi'     => 'required',
            'tanggal'     => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dokumentasi = Dokumentasi::find($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/dokumentasis', $image->hashName());

            Storage::delete('public/dokumentasis/'.basename($dokumentasi->image));

            $dokumentasi->update([
                'image'     => $image->hashName(),
                'judul'     => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal'     => $request->tanggal,
                
            ]);

        } else {

            $dokumentasi->update([
                'judul'     => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal'     => $request->tanggal,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Dokumentasi Berhasil Diubah!',
            'data'    => $dokumentasi
        ]);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy($id)
    {

        //find post by ID
        $dokumentasi = Dokumentasi::find($id);

        //delete image
        Storage::delete('public/dokumentasis/'.basename($dokumentasi->image));

        //delete post
        $dokumentasi->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Dokumentasi Berhasil Dihapus!',
            'data'    => null
        ]);
    }
}