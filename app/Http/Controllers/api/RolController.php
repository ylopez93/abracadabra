<?php

namespace App\Http\Controllers\api;

use App\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRolPost;
use App\Http\Controllers\Controller;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rol = Rol::all();
        return $this->successResponse([$rol,'Rol retrieved successfully.']);
    }

    public function findUsersByRol($rol){

        $users = DB::table('users')->select('*')
        ->where('rol_id','=',$rol)
        ->whereNull('deleted_at')
        ->get();

        return $this->successResponse([$users, 'Users retrieved successfully.']);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_rol = new StoreRolPost();
        $validator = $request->validate($v_rol->rules());
        if($validator){
           $rol = new Rol();
           $rol->name = $request['name'];
           $rol->save();

        // $product = Product::create($request);
        return $this->successResponse([$rol, 'Rol created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        $v_rol = new StoreRolPost();
        $validator = $request->validate($v_rol->rules());
        if($validator){
           $rol->name = $request['name'];
           $rol->save();

        return $this->successResponse([$rol, 'Rol updated successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return $this->successResponse('Rol deleted successfully.');
    }
}
