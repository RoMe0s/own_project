<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 10/7/16
 * Time: 4:58 PM
 */

namespace App\Traits\Controllers;

use App\Models\Category;


trait ProccessCategoriesTrait
{
    public function getAdminCategoriesList()
    {
        $categories = [];
        foreach (Category::with('translations')->get() as $category) {
            $categories[$category->id] = $category->name;
        }

        return $categories;
    }

    public function proccessWithCategories($data)
    {
        if(empty($data['category_id'])) {
            $data['category_id'] = NULL;
        }
        return $data;
    }
}