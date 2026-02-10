<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateUserRequest;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('username')){
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request -> has ('email')){
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->query('trashed')=== 'true') {
            $query->onlyTrashed();
        }

        $users = $query->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if (!isset($data['hiring_date'])){
            $data['hiring_date'] = Carbon::now()->toDateString();
        }

        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {//--------- Metodod Show ----------------
    // Busca por id, find - ignora el softdelete, si no devuelve error 404
        $user = User::find($id);

        if(!$user) {
            return response ()->json([
                'messsage' => 'Usuario no encontrado'
            ], 404 );
        }
        return response()->json(UserResource::make($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        return $this->partialUpdate($request, $id);
    }

    public function partialUpdate(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json(UserResource::make($user));
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response() ->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $users->delete();
        return response()->json([
            'message' => 'El usuario ha sido elimidado correctamente'
        ]);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user){
            return response()->json([
                'message' => 'Usuario no encontrado.'
        ], 404);
        }

        if ($user->deleted_at === null){
            return response()->json([
                'message' => 'El usuario no está eliminado.'
        ]);
        }

        $user->restore();
        return response()->json([
            'message'=>'Usuario restaurado correctamente'
        ]);

    }
}
