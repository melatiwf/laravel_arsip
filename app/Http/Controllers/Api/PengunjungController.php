<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PengunjungResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengunjungController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts


        $pengunjungs = Pengunjung::latest()->paginate(5);

        //return collection of posts as a resource
        return response()->json([
            'success' => true,
            'message' => 'List Data Pengunjung',
            'data'    => $pengunjungs
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
            'nama'     => 'required',
            'jenis_kelamin'     => 'required',
            'asal_instansi'     => 'required',
            'jumlah'     => 'required',
            'email'     => 'required',

        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/pengunjungs', $image->hashName());

         //create post
         $pengunjung = Pengunjung::create([
             'nama'     => $request->nama,
             'jenis_kelamin'     => $request->jenis_kelamin,
             'asal_instansi'     => $request->asal_instansi,
             'jumlah'     => $request->jumlah,
             'email'     => $request->email,
           
         ]);

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Pengunjung Berhasil Ditambahkan!',
            'data'    => $pengunjung
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
        $pengunjung = Pengunjung::find($id);

        //return single post as a resource
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Pengunjung!',
            'data'    => $pengunjung
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
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'jenis_kelamin'     => 'required',
            'asal_instansi'     => 'required',
            'jumlah'     => 'required',
            'email'     => 'required',
            
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $pengunjung = Pengunjung::find($id);

        //check if image is not empty
        // if ($request->hasFile('image')) 

        //     //upload image
        //     $image = $request->file('image');
        //     $image->storeAs('public/pengunjungs', $image->hashName());

        //     //delete old image
        //     Storage::delete('public/pengunjungs/'.basename($pengunjung->image));
            
            //update post with new image
         $pengunjung->update([
                  'nama'     => $request->nama,
                  'jenis_kelamin'     => $request->jenis_kelamin,
                  'asal_instansi'     => $request->asal_instansi,
                  'jumlah'     => $request->jumlah,
                  'email'     => $request->email,
                
              ]); 

        {

             //update post without image
             $pengunjung->update([
                 'nama'     => $request->nama,
                 'jenis_kelamin'     => $request->jenis_kelamin,
                 'asal_instansi'     => $request->asal_instansi,
                 'jumlah'     => $request->jumlah,
                 'email'     => $request->email,
                
             ]);
         }

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Pengunjung Berhasil Diubah!',
            'data'    => $pengunjung
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
        $pengunjung = Pengunjung::find($id);

        //delete image
        Storage::delete('public/pengunjungs/'.basename($pengunjung->image));

        //delete post
        $pengunjung->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Pengunjung Berhasil Dihapus!',
            'data'    => null
        ]);
    }
}