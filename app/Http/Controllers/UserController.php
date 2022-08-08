<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::all()->where('etat','=',1)->sortBy('prenom');

            return $this->sendResponse(UserResource::collection($users), 'Liste des utilisateurs');
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
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|min:3|max:60',
            'nom' => 'required|min:3|max:60',
            'email' => 'required|email',

        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt('passer123');
        $user = User::create($input);
        // $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        $user;

        return $this->sendResponse($user, 'User added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user == null) return $this->sendError("aucun user avec l'id $id trouve");
        return $this->sendResponse(new UserResource($user), 'user retrouve');
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
        $validator = Validator::make($input, [
            'prenom' => 'required|min:3|max:60',
            'nom' => 'required|min:3|max:60',
            'email' => 'required|email',
            'password'=> 'min:8|required_with:confirmation|same:confirmation',
            'confirmation'=> 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::Find($id);
        if ($user == null) return $this->sendError("aucun user avec l'id $id trouve");
        $user->nom = $input["nom"];
        $user->prenom = $input["prenom"];
        $user->email = $input["email"];
        $user->password = bcrypt($input["password"]);
        $user->update();
        return $this->sendResponse(new UserResource($user), 'utilisateur mise a jour.');
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user == null) return $this->sendError("aucun user avec l'id $id trouve");
        $user->etat = 0;
        $user->update();
        $user->tokens()->delete();
        return $this->sendResponse([], 'utilisateur supprimée.');
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user()->getAuthIdentifier();
            $user = User::find($user);
            if($user->etat == 0) return $this->sendError('Unauthorized.',['error'=>"cette utilisateur n'a plus de droit d'acces"]);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['nom'] =  $user->prenom." ".$user->nom;
            $success['email'] = $user->email;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([],"deconnecte");
    }
}
