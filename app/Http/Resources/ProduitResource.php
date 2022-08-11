<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $produits = [];
        $users = $this->user()->get()[0];
        $categorie = $this->categorie()->get()[0];
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'stock'=>$this->stock,
            'categorie'=> ['id'=>$categorie->id,'nom'=>$categorie->nom],
            'user'=>['id'=>$users->id,'nom'=>$users->prenom." ".$users->nom],
            // 'created_at' => $this->created_at->format('d/m/Y'),
            // 'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
