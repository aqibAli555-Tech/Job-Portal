<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\Post\CreateOrEdit\Traits\CategoriesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends FrontController
{
    use CategoriesTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoriesHtml(Request $request)
    {
        $languageCode = $request->get('languageCode');
        $catId = $request->get('catId');
        $catId = !empty($catId) ? $catId : null;

        // Get categories, parent & children
        $data = $this->categories($catId);


        // Get categories list buffer
        $html = getViewContent('post.createOrEdit.inc.category.select', $data);

        // Send JSON Response
        $result = [
            'html' => $html,
            'hasChildren' => $data['hasChildren'],
            'category' => $data['category'],
            'parent' => (!empty($data['category']->parent)) ? $data['category']->parent : null,
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
