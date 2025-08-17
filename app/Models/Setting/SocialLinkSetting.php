<?php

namespace App\Models\Setting;

class SocialLinkSetting
{
    public static function getValues($value, $disk)
    {
        if (empty($value)) {

            $value['facebook_page_url'] = '#';
            $value['twitter_url'] = '#';
            $value['google_plus_url'] = '#';
            $value['linkedin_url'] = '#';
            $value['pinterest_url'] = '#';
            $value['instagram_url'] = '#';

        } else {

            if (!isset($value['facebook_page_url'])) {
                $value['facebook_page_url'] = '';
            }
            if (!isset($value['twitter_url'])) {
                $value['twitter_url'] = '';
            }
            if (!isset($value['google_plus_url'])) {
                $value['google_plus_url'] = '';
            }
            if (!isset($value['linkedin_url'])) {
                $value['linkedin_url'] = '';
            }
            if (!isset($value['pinterest_url'])) {
                $value['pinterest_url'] = '';
            }
            if (!isset($value['instagram_url'])) {
                $value['instagram_url'] = '';
            }

        }

        return $value;
    }

    public static function setValues($value, $setting)
    {
        return $value;
    }

    public static function getFields($diskName)
    {
        $fields = [
            [
                'name' => 'facebook_page_url',
                'label' => trans('admin.Facebook Page URL'),
                'type' => 'text',
            ],
            [
                'name' => 'twitter_url',
                'label' => trans('admin.Twitter URL'),
                'type' => 'text',
            ],
            [
                'name' => 'google_plus_url',
                'label' => trans('admin.TikTok URL'),
                'type' => 'text',
            ],
            [
                'name' => 'linkedin_url',
                'label' => trans('admin.LinkedIn URL'),
                'type' => 'text',
            ],
            [
                'name' => 'pinterest_url',
                'label' => trans('admin.Pinterest URL'),
                'type' => 'text',
            ],
            [
                'name' => 'instagram_url',
                'label' => trans('admin.Instagram URL'),
                'type' => 'text',
            ],
        ];

        return $fields;
    }
}
