<?php

namespace App\Models\HomeSection;

class GetStats
{
    public static function getValues($value)
    {
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
                'name' => 'hide_on_mobile',
                'label' => trans('admin.hide_on_mobile_label'),
                'type' => 'checkbox',
                'hint' => trans('admin.hide_on_mobile_hint'),
            ],
            [
                'name' => 'active',
                'label' => trans('admin.Active'),
                'type' => 'checkbox',
            ],
        ];

        return $fields;
    }
}
