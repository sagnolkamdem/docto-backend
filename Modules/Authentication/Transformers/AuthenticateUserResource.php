<?php

namespace Modules\Authentication\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticateUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name'  => $this->first_name,
            'last_name'  => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'birthdate' => $this->birthdate,
            'email_verified_at' => $this->email_verified_at,
            'phone_number_verified_at' => $this->phone_number_verified_at,
            'can_login' => $this->can_login,
            'parent_id' => $this->parent_id,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'status' => $this->status,
            'address' => $this->address,
            'roles' => $this->userRoles(),
            'permissions' => $this->userPermissions(),
//            'roles' => $this->roles,
//            'permissions' => $this->roles()
//                ->with('permissions')
//                ->get()
//                ->pluck('permissions')
//                ->collapse(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'antecedents' => $this->antecedents,
            'profile_image' => $this->profile_photo_url,
        ];
    }
}
