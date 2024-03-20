<?php

namespace Modules\Notes\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NotesResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'patient_id' => $this->patient_id,
            'created_by' => $this->created_by,
            'created_by_practician' => $this->created_by_practician,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'author' => $this->author,
            'patient' => $this->patient,
        ];
    }
}
