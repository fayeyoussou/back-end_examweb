<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduitController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        try {
            $produit = Produit::all()->where('etat','=',1);
            return $this->sendResponse(ProduitResource::collection($produit),'produit retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error',$th->getMessage());
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request){
        // try {
        $input = $request->all();
        $validator = Validator::make($input,[
            'libelle'=> 'required|min:3|max:60',
            'stock'=>'required|numeric|min:0',
            'categorie_id'=>'required|numeric|min:1'
        ]);

        // $request->user()->currentAccessToken()->delete();
        // return $this->sendResponse($request->user()->id,'user');
        if($validator->fails()){
            return $this->sendError('Validator error.',$validator->errors());
        }
        $input["libelle"] =ucwords (strtolower( $input["libelle"]));
        $categorie = Categorie::find($input["categorie_id"]);
        $id = $input["categorie_id"];
        if($categorie ==null) return $this->sendError("aucun categorie avec l'id $id trouve");
        $input["user_id"] = $request->user()->id;
        $produit = new Produit();
        $produit->libelle = $input["libelle"];
        $produit->stock = $input["stock"];
        $produit->categorie()->associate($categorie);
        $produit->user()->associate($request->user()->id);
        $produit->etat = 1;
        $produit->save();
        return $this->sendResponse(
            // $input["nom"],
            new ProduitResource($produit),
            'Categorie created successfully');
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
    public function show ($id) {
        $produit = Produit::find($id);
        if($produit ==null || $produit->etat == 0) return $this->sendError("aucun categorie avec l'id $id trouve");
        return $this->sendResponse(new ProduitResource($produit),'Produit retrouve');
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
        $categorie = Categorie::find($input["categorie_id"]);
        $id = $input["categorie_id"];
        if($categorie ==null || $categorie->etat == 0) return $this->sendError("aucun categorie avec l'id $id trouve");
        $produit = Produit::Find($id);
        if($produit ==null || $produit->etat == 0) return $this->sendError("aucun produit avec l'id $id trouve");
        $validator = Validator::make($input, [
            'libelle'=> 'required|min:3|max:60',
            'stock'=>'required|numeric|min:0',
            'categorie_id'=>'required|numeric|min:1'
                ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $produit->libelle = $input["libelle"];
        $produit->stock = $input["stock"];
        $produit->categorie()->associate($categorie);
        $produit->update();

        return $this->sendResponse(new ProduitResource($produit), 'produit mise a jour.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produit = Produit::find($id);
        if($produit ==null || $produit->etat == 0) return $this->sendError("aucun produit avec l'id $id trouve");
        $produit->etat = 0;
        $produit->update();
        return $this->sendResponse([], 'Categorie supprimée.');
    }
}
