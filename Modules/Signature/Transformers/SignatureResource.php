<?php

namespace Modules\Signature\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SignatureResource extends JsonResource
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
            'path' => $this->path,
            'practician_id' => $this->practician_id,
            'filename' => $this->filename,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'practician' => $this->practician
        ];
    }
}
