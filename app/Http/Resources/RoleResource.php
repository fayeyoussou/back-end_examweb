<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $users = [];
        foreach ($this->users()->get() as $user) {
            $users[]=['id'=>$user->id,'nom'=>$user->prenom." ".$user->nom];
        }

        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'user'=>$users
        ];
    }
}
