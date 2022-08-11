<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MouvementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $produit = $this->produit()->get()[0];
        return [
            'id'=>$this->id,
            'type'=>$this->type()->get()[0]->nom,
            'date' => $this->date_mouvement,
            'quantite' => $this->quantite,
            'prix' => $this->prix,
            'produit' => ['id'=>$produit->id,'libelle'=>$produit->libelle]
        ];
    }
}
