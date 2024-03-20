<?php

namespace Modules\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class SessionController extends CoreController
{
    public function index(Request $request)
    {
        return $this->successResponse(__('Sessions gotten successfully'),[
            "sessions" => DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->orderBy('last_activity', 'desc')
//                ->limit(3)
                ->get()
                ->map(function ($session) {
                    return (object)[
                        'id' => $session->id,
                        'user_agent' => $session->user_agent,
                        'agent' => $this->createAgent($session),
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === request()->session()->getId(),
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'location' => Location::get($session->ip_address),
                        'payload' => $session->payload,
                        'is_active' => $session->is_active
                    ];
                })
        ], 200);
    }

    public function show($id)
    {
        return $this->successResponse(__('Session gotten successfully'),[
            "session" => DB::table('sessions')->where('id',$id)
                ->get()
                ->map(function ($session) {
                    return (object)[
                        'id' => $session->id,
                        'user_agent' => $session->user_agent,
                        'agent' => $this->createAgent($session),
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === request()->session()->getId(),
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'location' => Location::get($session->ip_address),
                        'payload' => $session->payload,
                        'is_active' => $session->is_active
                    ];
                }),
        ], 200);
    }

    public function delete($id)
    {
        DB::table('sessions')->where('id',$id)->delete();

        return $this->successResponse(__('Session has been deleted successfully'),[]);
    }

    public function createAgent($session)
    {
        $agent = new Agent();
        $agent->setUserAgent($session->user_agent);

        return [
            'device' => $agent->device(),
            'browser' => $agent->browser() ." ". $agent->version($agent->browser()),
            'is_mobile' => !$agent->isMobile(),
            'platform' => $agent->platform() ." ". $agent->version($agent->platform())
        ];
    }
}
