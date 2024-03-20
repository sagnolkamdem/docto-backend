<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatListResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'members' => $this->users,
            'unread' => $this->messages()
                ->whereNot('user_id',  auth()
                    ->guard('sanctum')
                    ->user()->id)
                ->where('status', 'sent')->orWhere('status', 'received')
                ->count(),
            'last_message' => new MessageResource($this->messages()->latest()->first()),
        ];
    }
}
