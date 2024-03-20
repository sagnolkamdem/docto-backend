<?php

namespace Modules\Document\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'type' => $this->type,
            'path' => $this->path,
            'patient_id' => $this->patient_id,
            'filename' => $this->filename,
            'created_by' => $this->created_by,
            'created_by_practician' => $this->created_by_practician,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'metadata' => $this->metadata,
            'author' => $this->created_by_practician ? $this->authorPro : $this->author,
            'patient' => $this->patient,
            'files' => $this->documentFiles->isEmpty() ? [[
                    'document_id' => $this->id,
                    'filename' => $this->filename,
                    'path' => $this->path,
                ]] : $this->documentFiles
        ];
    }
}
