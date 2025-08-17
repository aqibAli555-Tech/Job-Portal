<?php

namespace App\Models\Setting\Traits;

trait WysiwygEditorsTrait
{
    /**
     * @return array
     */
    public static function wysiwygEditors()
    {
        return [
            'none' => trans('admin.wysiwyg_editor_none'),
            'tinymce' => trans('admin.wysiwyg_editor_tinymce'),
            'ckeditor' => trans('admin.wysiwyg_editor_ckeditor'),
            'summernote' => trans('admin.wysiwyg_editor_summernote'),
            'simditor' => trans('admin.wysiwyg_editor_simditor'),
        ];
    }
}
