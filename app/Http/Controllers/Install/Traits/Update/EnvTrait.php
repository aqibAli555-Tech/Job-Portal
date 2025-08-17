<?php

namespace App\Http\Controllers\Install\Traits\Update;

use Jackiedo\DotenvEditor\Facades\DotenvEditor;

trait EnvTrait
{
    /**
     * Update the current version to last version
     *
     * @param $last
     */
    private function setCurrentVersion($last)
    {
        if (!DotenvEditor::keyExists('APP_VERSION')) {
            DotenvEditor::addEmpty();
        }
        DotenvEditor::setKey('APP_VERSION', $last);
        DotenvEditor::save();
    }
}
