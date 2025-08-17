<?php

namespace App\Http\Controllers\Admin;

// Increase the server resources
$iniConfigFile = __DIR__ . '/../../../Helpers/Functions/ini.php';
if (file_exists($iniConfigFile)) {
    include_once $iniConfigFile;
}

use App\Http\Controllers\Admin\Traits\SettingsTrait;
use App\Http\Requests\Admin\SettingRequest as StoreRequest;
use App\Http\Requests\Admin\SettingRequest as UpdateRequest;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SettingController extends PanelController
{
    use SettingsTrait;

    public function __construct()
    {

        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Setting');
        $this->xPanel->addClause('where', 'active', 1);
        $this->xPanel->setEntityNameStrings(trans('admin.general setting'), trans('admin.general settings'));
        $this->xPanel->setRoute(admin_uri('settings'));
        $this->xPanel->enableReorder('name', 1);
        $this->xPanel->allowAccess(['reorder']);
        $this->xPanel->denyAccess(['create', 'delete']);
        $this->xPanel->setDefaultPageLength(100);
        if (!request()->input('order')) {
            $this->xPanel->orderBy('lft', 'ASC');
            $this->xPanel->orderBy('id', 'ASC');
        }

        $this->xPanel->removeButton('update');
        $this->xPanel->addButtonFromModelFunction('line', 'configure', 'configureBtn', 'beginning');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name' => 'name',
            'label' => "Setting",
            'type' => "model_function",
            'function_name' => 'getNameHtml',
        ]);
        $this->xPanel->addColumn([
            'name' => 'description',
            'label' => "",
        ]);

        // FIELDS
        // ...
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return $this->updateTrait($request);
    }
}
