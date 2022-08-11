<?php

namespace App\Http\Controllers;

use App\Http\Resources\MouvementResource;
use App\Models\Mouvement;
use App\Models\Produit;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MouvementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $mouvement = Mouvement::where('etat', '=', 1)->orderBy('date_mouvement', 'asc')
                ->get();
            return $this->sendResponse(MouvementResource::collection($mouvement), 'Liste des mouvements retrouves');
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'date_mouvement' => "required|date|before_or_equal:now",
            'type_id' => 'required|numeric|min:0|max:3',
            'produit_id' => 'required|numeric|min:0',
            'quantite' => 'required|numeric|min:1',
            'prix' => 'required|numeric|min:5'

        ]);

        // $request->user()->currentAccessToken()->delete();
        // return $this->sendResponse($request->user()->id,'user');
        if ($validator->fails()) {
            return $this->sendError('Validator error.', $validator->errors());
        }
        $input["etat"] =  1;
        $produit = Produit::find($input["produit_id"]);
        if ($produit == null) return $this->sendError("error", ["produit non trouve dans la base"]);
        $type = Type::find($input["type_id"]);
        if ($type == null) return $this->sendError("error", ["type non trouve dans la base"]);
        if ($input["type_id"] == 1) $produit->stock += $input["quantite"];
        else if ($produit->stock > $input["quantite"]) $produit->stock -= $input["quantite"];
        else return $this->sendError("error", ["quantite insuffisante"]);


        $produit->update();
        $mouvement = Mouvement::create($input);
        return $this->sendResponse(
            new MouvementResource($mouvement),
            'mouvement crée '
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mouvement = Mouvement::find($id);
        if ($mouvement == null) return $this->sendError("aucun mouvement avec l'id $id trouve");
        return $this->sendResponse(new MouvementResource($mouvement), 'mouvement retrieved successfully');
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


        // Validation du formulaire et recherche des entites
        $mouvement = Mouvement::Find($id);
        if ($mouvement == null) return $this->sendError("aucun mouvement avec l'id $id trouve");
        $input = $request->all();
        $validator = Validator::make($input, [
            'date_mouvement' => "required|date|before_or_equal:now",
            'type_id' => 'required|numeric|min:0|max:3',
            'produit_id' => 'required|numeric|min:0',
            'quantite' => 'required|numeric|min:1',
            'prix' => 'required|numeric|min:5'
        ]);

        $produit = $mouvement->produit()->get()[0];

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        // suppression des effets du mouvement precedent
        if ($mouvement->type()->get()[0]->id == 1 && $produit->stock < $input["quantite"]) return $this->sendError("erreur requete", "quantite produit insuffisante");
        else if($mouvement->type()->get()[0]->id == 1 ) $produit->stock -= $mouvement->quantite;
        else $produit->stock +=$mouvement->quantite;
        $produit->update();
        // ajout de la mise a jour du mouvement
        $produitnew = Produit::find($input["produit_id"]);
        if ($produitnew == null) return $this->sendError("error", ["produit non trouve dans la base"]);
        if ($input["type_id"] == 1) $produitnew->stock += $input["quantite"];
        else if ($produitnew->stock > $input["quantite"]) $produitnew->stock -= $input["quantite"];
        else return $this->sendError("error", ["quantite insuffisante"]);

        // Modifier mouvement
        $mouvement->date_mouvement = $input["date_mouvement"];
        $mouvement->type()->associate(Type::find($input["type_id"]));
        $mouvement->produit()->associate($produitnew);
        $mouvement->quantite = $input["quantite"];
        $mouvement->prix = $input["prix"];

        // mise a jour des tables
        $produitnew->update();
        $mouvement->update();
        return $this->sendResponse(new mouvementResource($mouvement), 'mouvement mise a jour.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mouvement = Mouvement::find($id);
        // $mouvement->delete();
        $mouvement->etat = 0;
        $mouvement->update();
        return $this->sendResponse([], 'Categorie supprimée.');
    }
}
