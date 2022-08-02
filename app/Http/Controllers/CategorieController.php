<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategorieResource;
use Illuminate\Http\Request;
use App\Models\Categorie;
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
        $product = Categorie::all();
        return $this->sendResponse(CategorieResource::collection($product),'product retrieved successfully');
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
            'nom'=> 'required|min:6|max:60'
        ]);
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse($request->user()->id,'user');
        if($validator->fails()){
            return $this->sendError('Validator error.',$validator->errors());
        }
        $categorie = Categorie::create($input);
        return $this->sendResponse(new CategorieResource($categorie),'Categorie created successfully');
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show ($id) {
        $categorie = Categorie::find($id);
        if(isNull($categorie)) return $this->sendError('Product not found');
        return $this->sendResponse(new CategorieResource($categorie),'Categorie retrieved successfully');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorie $categorie)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nom' => 'required|min:6|max:60',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $categorie->nom = $input['nom'];
        $categorie->update();

        return $this->sendResponse(new CategorieResource($categorie), 'categorie updated successfully.');
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

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
