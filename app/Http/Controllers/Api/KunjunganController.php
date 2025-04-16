<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KunjunganResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KunjunganController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $kunjungans = Kunjungan::with('pengunjungs')->get();


        $kunjungans = Kunjungan::latest()->paginate(5);

        //return collection of posts as a resource
        return new KunjunganResource(true, 'List Data Kunjungan', $kunjungans);
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
            'nama_instansi'     => 'required',
            'tanggal'     => 'required',
            'tujuan_kunjungan'     => 'required',
            'pengunjungs_id'   => 'required',


        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/kunjungans', $image->hashName());

        //create post
        $kunjungan = Kunjungan::create([
            'image'     => $image->hashName(),
            'nama_instansi'     => $request->nama_instansi,
            'tanggal'     => $request->tanggal,
            'tujuan_kunjungan'     => $request->tujuan_kunjungan,
            'pengunjungs_id'   => $request->pengunjungs_id,

           
        ]);

        //return response
        return new KunjunganResource(true, 'Data Kunjungan Berhasil Ditambahkan!', $kunjungan);
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
        $kunjungan = Kunjungan::find($id);
        
        //return single post as a resource
        return new KunjunganResource(true, 'Detail Data Kunjungan!', $kunjungan);
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
            'nama_instansi'     => 'required',
            'tanggal'     => 'required',
            'tujuan_kunjungan'     => 'required',
            'pengunjungs_id' => 'required',


            
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $kunjungans = Kunjungan::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/kunjungans', $image->hashName());

            //delete old image
            Storage::delete('public/kunjungans/'.basename($kunjungan->image));

            //update post with new image
            $kunjungan->update([
                'image'     => $image->hashName(),
                'nama_instansi'     => $request->nama_instansi,
                'tanggal'     => $request->tanggal,
                'tujuan_kunjungan'     => $request->tujuan_kunjungan,
                'pengunjungs_id'   => $request->pengunjungs_id,

                
            ]);

        } else {

            //update post without image
            $kunjungan->update([
                'nama_instansi'     => $request->nama_instansi,
                'tanggal'     => $request->tanggal,
                'tujuan_kunjungan'     => $request->tujuan_kunjungan,
                'pengunjungs_id'   => $request->pengunjungs_id,

            ]);
        }

        //return response
        return new KunjunganResource(true, 'Data Kunjungan Berhasil Diubah!', $kunjungan);
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
        $kunjungan = Kunjungan::find($id);

        //delete image
        Storage::delete('public/kunjungans/'.basename($kunjungan->image));

        //delete post
        $kunjungan->delete();

        //return response
        return new KunjunganResource(true, 'Data Kunjungan Berhasil Dihapus!', null);
    }
}