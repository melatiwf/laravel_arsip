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
        return new DokumentasiResource(true, 'List Data Dokumentasi', $dokumentasis);
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
        return new DokumentasiResource(true, 'Data Dokumentasi Berhasil Ditambahkan!', $dokumentasi);
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
        return new DokumentasiResource(true, 'Detail Data Dokumentasi!', $dokumentasi);
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
        //define validation rules
        $validator = Validator::make($request->all(), [
            'judul'     => 'required',
            'deskripsi'     => 'required',
            'tanggal'     => 'required',
            
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $dokumentasi = Dokumentasi::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/dokumentasis', $image->hashName());

            //delete old image
            Storage::delete('public/dokumentasis/'.basename($dokumentasi->image));

            //update post with new image
            $dokumentasi->update([
                'image'     => $image->hashName(),
                'judul'     => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal'     => $request->tanggal,
                
            ]);

        } else {

            //update post without image
            $dokumentasi->update([
                'judul'     => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal'     => $request->tanggal,
            ]);
        }

        //return response
        return new DokumentasiResource(true, 'Data Dokumentasi Berhasil Diubah!', $dokumentasi);
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
        return new DokumentasiResource(true, 'Data Dokumentasi Berhasil Dihapus!', null);
    }
}