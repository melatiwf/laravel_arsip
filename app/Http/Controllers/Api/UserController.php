<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


//import Resource "PostResource"
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $users = User::latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data' => $users
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ADA KESALAHAN',
                'data' => $validator->erros()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Sukses register',
            'data' => $success
        ]);
    }
    /**
     * show
     *
     * @param  mixed 
     * @return void
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data User!',
            'data' => $user
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
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data User Berhasil Diubah!',
            'data' => $user
        ]);
    }
    public function destroy($id)
    {

        //find post by ID
        $user = User::find($id);

        //delete image

        //delete post
        $user->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data User Berhasil Dihapus!',
            'data' => null
        ]);
    }
}