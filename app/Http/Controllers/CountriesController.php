<?php

namespace App\Http\Controllers;

use Torann\LaravelMetaTags\Facades\MetaTag;

class CountriesController extends FrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];
        // Meta Tags
        $data['title'] = t('countries');
        MetaTag::set('title', getMetaTag('title', 'countries'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'countries')));
        MetaTag::set('keywords', getMetaTag('keywords', 'countries'));
        return appView('countries', $data);
    }
}
