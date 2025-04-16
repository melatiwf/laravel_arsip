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

        return new UserResource(true, 'List Data User ', $users);
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        if ($validator->fails()){
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
        //return response
        return new UserResource(true, 'Data User Berhasil Ditambahkan!', $user);
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

        return new UserResource(true, 'Detail Data User!', $user);
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
    
        // Find the category by ID
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }
    
        // Update the category
        $user->update([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
            'password_confirmation'=> $request->password_confirmation
        ]);
    
        // Return response
        return new UserResource(true, 'Data User Berhasil Diubah!', $user);
    }
        public function destroy($id)
    {

        //find post by ID
        $user = User::find($id);

        //delete image

        //delete post
        $user->delete();

        //return response
        return new UserResource(true, 'Data User Berhasil Dihapus!', null);
    }
}