<?php

namespace Modules\User\Traits;

trait HasProfilePhoto
{
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->attributes['profile_photo_url'] != null || $this->attributes['profile_photo_url'] != "") {
            return $this->attributes['profile_photo_url'];
        }

        return $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->first_name.' '.$this->last_name).'&color=226520&background=E3FFE3';
    }
}
