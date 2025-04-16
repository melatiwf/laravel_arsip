<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Pengumuman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//import Resource "PostResource"
use App\Http\Resources\PengumumanResource;

//import Facade "Storage"
use Illuminate\Support\Facades\Storage;

//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts


        $pengumumans = Pengumuman::latest()->paginate(5);

        //return collection of posts as a resource
        return new PengumumanResource(true, 'List Data Pengumuman', $pengumumans);
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
            'tanggal_dibuat'     => 'required',
            'tampil_hingga'     => 'required',

        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/pengumumans', $image->hashName());

        //create post
        $pengumuman = Pengumuman::create([
            'image'     => $image->hashName(),
            'judul'     => $request->judul,
            'tanggal_dibuat'     => $request->tanggal_dibuat,
            'tampil_hingga'     => $request->tampil_hingga,
           
        ]);

        //return response
        return new PengumumanResource(true, 'Data Pengumuman Berhasil Ditambahkan!', $pengumuman);
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
        $pengumuman = Pengumuman::find($id);

        //return single post as a resource
        return new PengumumanResource(true, 'Detail Data Pengumuman!', $pengumuman);
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
            'tanggal_dibuat'     => 'required',
            'tampil_hingga'     => 'required',
            
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $pengumuman = Pengumuman::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/pengumumans', $image->hashName());

            //delete old image
            Storage::delete('public/pengumumans/'.basename($pengumuman->image));

            //update post with new image
            $pengumuman->update([
                'judul'     => $request->judul,
                'tanggal_dibuat'     => $request->tanggal_dibuat,
                'tampil_hingga'     => $request->tampil_hingga,
                
            ]);

        } else {

            //update post without image
            $pengumuman->update([
                'image'     => $image->hashName(),
                'judul'     => $request->judul,
                'tanggal_dibuat'     => $request->tanggal_dibuat,
                'tampil_hingga'     => $request->tampil_hingga,
            ]);
        }

        //return response
        return new PengumumanResource(true, 'Data Pengumuman Berhasil Diubah!', $pengumuman);
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
        $pengumuman = Pengumuman::find($id);

        //delete image
        Storage::delete('public/pengumumans/'.basename($pengumuman->image));

        //delete post
        $pengumuman->delete();

        //return response
        return new PengumumanResource(true, 'Data Pengumuman Berhasil Dihapus!', null);
    }
}