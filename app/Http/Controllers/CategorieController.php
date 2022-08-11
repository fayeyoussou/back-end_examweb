<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategorieResource;
use App\Http\Resources\ProduitResource;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class CategorieController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $categorie = Categorie::all();
        return $this->sendResponse(CategorieResource::collection($categorie),'categorie retrieved successfully');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request){
        $input = $request->all();
        $validator = Validator::make($input,[
            'nom'=> 'required|min:3|max:60'
        ]);

        // $request->user()->currentAccessToken()->delete();
        // return $this->sendResponse($request->user()->id,'user');
        if($validator->fails()){
            return $this->sendError('Validator error.',$validator->errors());
        }
        $input["nom"] =ucwords (strtolower( $input["nom"]));
        $categorie = Categorie::create($input);
        return $this->sendResponse(
            $input["nom"],
            new CategorieResource($categorie),
            'Categorie created successfully');
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $categorie = Categorie::find($id);
        if($categorie ==null) return $this->sendError("aucun categorie avec l'id $id trouve");
        return $this->sendResponse(new CategorieResource($categorie),'Categorie retrieved successfully');
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
        $categorie = Categorie::Find($id);
        if($categorie ==null) return $this->sendError("aucun categorie avec l'id $id trouve");
        $input = $request->all();
        $validator = Validator::make($input, [
            'nom' => 'required|min:3|max:60',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $categorie->nom =ucwords (strtolower( $input["nom"]));
        ;
        $categorie->update();

        return $this->sendResponse(new CategorieResource($categorie), 'Categorie mise a jour.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return $this->sendResponse([], 'Categorie supprimée.');
    }

    public function showProduits($id){
        $categorie = Categorie::find($id);
        if ($categorie == null) $this->sendError('Introuvable',['message'=>'categorie non trouve']);

        $produits = Produit::where('etat', '=','1')->where('categorie_id','=',$id)->get();
        return $this->sendResponse(ProduitResource::collection($produits),'Liste des produits');
    }
}
