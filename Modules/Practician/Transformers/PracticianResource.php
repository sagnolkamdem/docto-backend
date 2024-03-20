<?php

namespace Modules\Practician\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Establishment\Transformers\EstablishmentData;

class PracticianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name'  => $this->first_name,
            'last_name'  => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'birthdate' => $this->birthdate,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'is_active' => $this->is_active,
            'is_valid' => $this->is_valid,
            'accepts_new_patients' => $this->accepts_new_patients,
            'presentation' => $this->presentation,
            'slug' => $this->slug,
            'emergency' => $this->emergency,
            'head_quarter' => $this->head_quarter,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'profile_image' => $this->profile_photo_url,
            'expertises' => $this->expertises,
            'address' => $this->addresses,
            'commune' => $this->addresses->pluck('commune'),
            'speciality' => $this->specialityData->name??null,
            'establishment_head' => new EstablishmentData($this->establishmentData),
            'establishments' => EstablishmentData::collection($this->establishments),
            'roles' => $this->userRoles(),
            'permissions' => $this->userPermissions(),
        ];
    }
}
