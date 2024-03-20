<?php

namespace Modules\Chat\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\Message;
use Modules\Chat\Entities\MessageAttachment;
use Modules\Chat\Events\MessageSent;
use Modules\Chat\Transformers\ChatResource;
use Modules\Chat\Transformers\MessageResource;
use Modules\Core\Helpers\PushNotification;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Document\Entities\DocumentFile;
use Modules\Practician\Entities\Practician;

class MessageController extends CoreController
{
    public function getByChat(Request $request)
    {
        $messages = Message::query()
            ->whereHas('chat', function (Builder $query) use ($request) {
                $query->where('id', $request->id);
            })
            ->limit($request->limit??50)
            ->latest()
            ->get();

        return $this->json(['messages' => MessageResource::collection($messages)]);
    }

    public function store(Request $request)
    {
        if ($request->is_new == true) {
            if ($request->is_group === true) {
                $users = Practician::whereIn('id', $request->users)->get();

                $chat = Chat::create([
                    'name' => $request->name ?? "Group de ".auth()
                            ->guard('pro')
                            ->user()->first_name,
                    'type' => 'group',
                ]);

                $chat->users()->attach($users->pluck('id')->toArray());
            } else {
                $user1 = auth()
//                    ->guard('pro')
                    ->user();
                $user2 = Practician::findOrFail($request->receiver_id);

                $chat = Chat::create([
                    'name' => $request->name ?? $user2->first_name,
                    'type' => 'private',
                ]);

                $chat->users()->attach([$user1->id, $user2->id]);
            }
        }
        $user = Practician::findOrFail($request->user_id ??
                    auth()
//                    ->guard('pro')
                    ->user()->id
                );
        $data = [
            'user_id' => $user->id,
            'chat_id' => $chat->id ?? $request->chat_id,
            'body' => $request->body,
            'parent_id' => $request->parent_id ?: null,
        ];
        $message = Message::create($data);
        if ($request->file('files')){
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = $file->getFilename().'-'.time().'.'.$file->extension();
                $path = config('app.url') . '/storage/' . $file->storeAs('message-attachments', $name, 'public');
                $messageAttachment = new MessageAttachment;
                $messageAttachment->message_id = $message->id;
                $messageAttachment->file_name = $name;
                $messageAttachment->file = $path;
                $messageAttachment->mime_type = $file->getMimeType();
                $messageAttachment->save();
            }
        }

        broadcast(new MessageSent($user,$message))->toOthers();

//        $notifications = new PushNotification();
//        $res = $notifications->setContents(['en' => json_encode($message),'fr' => json_encode($message)])
//            ->setIncludeExternalUserIds($message->chat->users()->where('id', '!=', $user->id)->pluck('email')->toArray())
//            ->trigger();
//        dd($res);
        return $this->successResponse(__('message successfully sent'), ['message' => new MessageResource($message)]);
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);

        return $this->successResponse(__('Got message successfully'), [
            'message' => new MessageResource($message)
        ]);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $data = [
            'body' => $request->body,
            'status' => $request->status,
        ];

        $message->update($data);

        return $this->successResponse(
            __('Update message successfully'),
            ['message' => $message]
        );
    }

    public function destroy(Request $request,$id)
    {
        $message = Message::findOrFail($id);
        $message->update([
            'deleted' => true,
            'deleted_at' => now(),
        ]);

        return $this->successResponse(__('Deleted message successfully!'));
    }
}
