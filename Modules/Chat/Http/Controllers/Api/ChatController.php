<?php

namespace Modules\Chat\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\Message;
use Modules\Chat\Http\Requests\CreateChatRequest;
use Modules\Chat\Transformers\ChatListResource;
use Modules\Chat\Transformers\ChatResource;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Practician\Entities\Practician;

class ChatController extends CoreController
{
    public function getAllChats(Request $request)
    {
        $chats = Chat::query()
            ->with(array('messages' => function($q) {
                $q->orderBy('created_at', 'DESC');
            }))
            ->where('chats.deleted_at', '=', null)
            ->whereHas('users', function ($builder) {
                $builder->where('id',  auth()->user()->id);
            })
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('chats.type', 'like', "%".$request->type."%");
            })
            ->orderByDesc('chats.created_at')
            ->selectRaw("chats.*, (SELECT MAX(created_at) from messages WHERE messages.chat_id=chats.id) as latest_message_on")
            ->orderBy("latest_message_on", "DESC")
            ->get();

        return $this->successResponse(__('Got chats successfully'), [
            'chats' => ChatListResource::collection($chats)
        ]);

    }

    public function store(CreateChatRequest $request)
    {
        if ($request->is_group == true) {
            $userIds = explode(',',$request->users);
            $users = Practician::whereIn('id', $userIds)->get();
            $chat = Chat::create([
                'name' => $request->name ?? "Group de ".auth()
//                ->guard('pro')
                        ->user()
                        ->first_name,
                'type' => 'group',
            ]);

            $chat->users()->attach($users->pluck('id')->toArray());
        } else {
            $user1 = auth()
//                ->guard('pro')
                ->user();
            $user2 = Practician::find($request->receiver_id);

            $chat = Chat::create([
                'name' => $user1->first_name . ' and ' . $user2->first_name,
                'type' => 'private',
            ]);

            $chat->users()->attach([$user1->id, $user2->id]);
        }
        return $this->successResponse(__('Created chats successfully'), [
            'chat' => new ChatResource($chat)
        ]);
    }

    public function show($id)
    {
        $chat = Chat::findOrFail($id);

        return $this->successResponse(__('Got chats successfully'), [
            'chat' => new ChatListResource($chat)
        ]);
    }

    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);
        $data = [
            'name' => $request->name,
        ];

        $chat->update($data);

        return $this->successResponse(
            __('Update chat successfully'),
            ['chat' => $chat]
        );
    }

    public function readMsg(Request $request, $id)
    {
        $userId = auth()
            ->guard('sanctum')
            ->user()->id;

        $messages = Message::query()->where('chat_id', $id)
            ->whereNot('user_id', $userId)
            ->where('status', 'sent')->orWhere('status', 'received')
//            ->get()
            ->update(['status' => 'read']);

//        foreach ($messages as $message) {
//            $message->status = 'read';
//            $message->save();
//        }

        return $this->successResponse(
            __('read chat successfully'),
            ['success' => true, 'messages' => $messages]
        );
    }

    public function receiveMsg(Request $request, $id)
    {
        $userId = auth()
                ->guard('sanctum')
            ->user()->id;

        $messages = Message::where('chat_id', $id)
            ->whereNot('user_id', $userId)
            ->where('status', 'sent')
//            ->get()
            ->update(['status' => 'received']);

        return $this->successResponse(
            __('receive chat successfully'),
            ['success' => true, 'messages' => $messages]
        );
    }

    public function destroy(Request $request,$id)
    {
        $chat = Chat::findOrFail($id);
        $chat->update([
            'deleted_at' => now(),
        ]);

        return $this->successResponse(__('Deleted chat successfully!'));
    }

    public function removeUsers(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);
        if ($chat->type == 'group') {
            $userIds = explode(',',$request->users);
            $users = Practician::whereIn('id', $userIds)->get();
            $chat->users()->detach($users->pluck('id')->toArray());
        }

        $chat->save();

        return $this->successResponse(
            __('Update chat successfully'),
            ['chat' => new ChatListResource($chat)]
        );
    }

    public function addUsers(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);
        if ($chat->type == 'group') {
            $userIds = explode(',',$request->users);
            $users = Practician::whereIn('id', $userIds)->get();
            $chat->users()->attach($users->pluck('id')->toArray());
        }

        $chat->save();

        return $this->successResponse(
            __('Update chat successfully'),
            ['chat' => new ChatListResource($chat)]
        );
    }
}
