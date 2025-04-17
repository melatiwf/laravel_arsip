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
        $kunjungans = Kunjungan::with('pengunjungs')->paginate();


        return response()->json([
            'success' => true,
            'message' => 'List Data Kunjungan',
            'data'    => $kunjungans
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
            'nama_instansi'     => 'required',
            'tanggal'     => 'required',
            'tujuan_kunjungan'     => 'required',
            'pengunjungs_id'   => 'required',


        ]);

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
        return response()->json([
            'success' => true,
            'message' => 'Data Kunjungan Berhasil Ditambahkan!',
            'data'    => $kunjungan
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
        $kunjungan = Kunjungan::find($id);
        
        //return single post as a resource
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Kunjungan!',
            'data'    => $kunjungan
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
            'nama_instansi'     => 'required',
            'tanggal'     => 'required',
            'tujuan_kunjungan'     => 'required',
            'pengunjungs_id' => 'required',


            
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kunjungan = Kunjungan::find($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/kunjungans', $image->hashName());

            Storage::delete('public/kunjungans/'.basename($kunjungan->image));

            $kunjungan->update([
                'image'     => $image->hashName(),
                'nama_instansi'     => $request->nama_instansi,
                'tanggal'     => $request->tanggal,
                'tujuan_kunjungan'     => $request->tujuan_kunjungan,
                'pengunjungs_id'   => $request->pengunjungs_id,

                
            ]);

        } else {

            $kunjungan->update([
                'nama_instansi'     => $request->nama_instansi,
                'tanggal'     => $request->tanggal,
                'tujuan_kunjungan'     => $request->tujuan_kunjungan,
                'pengunjungs_id'   => $request->pengunjungs_id,

            ]);
        }

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Kunjungan Berhasil Diubah!',
            'data'    => $kunjungan
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

        $kunjungan = Kunjungan::find($id);

        Storage::delete('public/kunjungans/'.basename($kunjungan->image));

        $kunjungan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Kunjungan Berhasil Dihapus!',
        ]);
    }
}