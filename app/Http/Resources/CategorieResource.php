<?php

namespace App\Http\Resources;

use App\Models\Categorie;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $produits = [];
        foreach ($this->produits()->get() as $produit) {
            $produits[] = ['id'=>$produit->id,'libelle'=>$produit->libelle];
        }
        // $categorie = Categorie::find($this->id);
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'produits'=> $produits,
            // 'created_at' => $this->created_at->format('d/m/Y'),
            // 'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
