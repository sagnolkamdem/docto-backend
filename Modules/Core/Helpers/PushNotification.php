<?php

namespace  Modules\Core\Helpers;

use Illuminate\Support\Facades\Http;

class PushNotification
{

    private $device_id;
    private $device_ids = [];
    private $targets = [];
    private $contents = [];
    private $headings = [
        'fr' => 'Tabiblib',
        'en' => 'Tabiblib'
    ];
    private $included_segments = [];
    private $excluded_segments = [];



    private $headers = [];

    private  $include_external_user_ids = [];

    public function __construct()
    {
        $this->headers =  [
            'Referer' => 'https://cosna-afrique.com',
            'Content-type' => 'application/json; charset=utf-8',
            'Access-Control-Allow-Origin' => 'https://tabiblib-services.com',
            'Authorization' => env('ONESIGNAL_API_KEY')
        ];
    }

    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param mixed $device_id
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceIds():array
    {
        return $this->device_ids;
    }

    /**
     * @param mixed $device_ids
     */
    public function setDeviceIds($device_ids)
    {
        $this->device_ids = $device_ids;
        return $this;
    }

    /**
     * @return array
     */
    public function getTargets(): array
    {
        return $this->targets;
    }

    /**
     * @param array $targets
     */
    public function setTargets(array $targets)
    {
        $this->targets = $targets;
        return $this;
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param array $contents
     */
    public function setContents(array $contents)
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeadings(): array
    {
        return $this->headings;
    }

    /**
     * @param array $headings
     */
    public function setHeadings(array $headings)
    {
        $this->headings = $headings;
        return $this;

    }

    /**
     * @return array
     */
    public function getIncludedSegments(): array
    {
        return $this->included_segments;
    }

    /**
     * @param array $included_segments
     */
    public function setIncludedSegments(array $included_segments)
    {
        $this->included_segments = $included_segments;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedSegments(): array
    {
        return $this->excluded_segments;
    }

    /**
     * @param array $excluded_segments
     */
    public function setExcludedSegments(array $excluded_segments)
    {
        $this->excluded_segments = $excluded_segments;
        return $this;
    }

    /**
     * @return array
     */
    public function getIncludeExternalUserIds(): array
    {
        return $this->include_external_user_ids;
    }

    /**
     * @param array $include_external_user_ids
     */
    public function setIncludeExternalUserIds(array $include_external_user_ids)
    {
        $this->include_external_user_ids = $include_external_user_ids;
        return $this;
    }


    public function trigger(): string
    {
        $payload = [
            "app_id" => env('ONESIGNAL_APP_KEY'),
            "contents" => $this->getContents(),
            "headings" => $this->getHeadings(),
        ];

        if(count($this->getDeviceIds()) > 0){
            $payload["included_segments"] = $this->getDeviceIds();
        }

        if(count($this->getIncludedSegments()) > 0){
            $payload["include_player_ids"] = $this->getIncludedSegments();
        }

        if(count($this->getExcludedSegments()) > 0){
            $payload["excluded_segments"] = $this->getExcludedSegments();
        }

        if(count($this->getIncludeExternalUserIds()) > 0){
            $payload["include_external_user_ids"] = $this->getIncludeExternalUserIds();
        }

       return  $this->send($payload);
    }

    private function send($payload): string
    {
        $request = Http::withHeaders($this->headers)->post(env('ONESIGNAL_URL'), $payload);
        return $request->body();
    }


}
