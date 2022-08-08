<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $role = Role::all()->sortBy('nom');
            return $this->sendResponse(RoleResource::collection($role), 'role retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nom' => 'required|min:3|max:10',
        ]);

        // $request->user()->currentAccessToken()->delete();
        // return $this->sendResponse($request->user()->id,'user');
        if ($validator->fails()) {
            return $this->sendError('Validator error.', $validator->errors());
        }
        $role = Role::create($input);
        return $this->sendResponse(
            // $input["nom"],
            $role,
            'Categorie created successfully'
        );
        // } catch(\Throwable $th){
        //     return $this->sendError($th);
        // }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if ($role == null) return $this->sendError("aucun role avec l'id $id trouve");
        return $this->sendResponse(new roleResource($role), 'role retrouve');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $role = Role::Find($id);
        if ($role == null) return $this->sendError("aucun role avec l'id $id trouve");
        $validator = Validator::make($input, [
            'nom' => 'required|min:3|max:10'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $role->nom = $input["nom"];

        $role->update();

        return $this->sendResponse(new RoleResource($role), 'role mise a jour.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role == null) return $this->sendError("aucun role avec l'id $id trouve");
        $role->remove();
        return $this->sendResponse([], 'Categorie supprimée.');
    }
}
