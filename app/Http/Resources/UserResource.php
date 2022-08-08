<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $roles = [];
        foreach ($this->roles()->get() as $role) {
            $roles[] = $role->nom;
        }
        return [
            'id' => $this->id,
            'prenom' => $this->prenom,
            'nom'=>$this->nom,
            'email'=>$this->email,
            'roles'=>$roles,

        ];
    }
}
