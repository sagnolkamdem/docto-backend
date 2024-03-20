<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->body,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'deleted' => $this->deleted,
            'deleted_by' => $this->deleted_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'sender' => $this->user,
            'chat' => $this->chat,
            'members' => $this->chat->users()->pluck('id'),
            'parent' => $this->parent,
            'attachments' => $this->attachments,
        ];
    }
}
