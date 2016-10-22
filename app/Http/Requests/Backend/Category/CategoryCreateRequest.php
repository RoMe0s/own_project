<?php

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\Request;

class CategoryCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^.*\.(' . implode('|', config('image.allowed_image_extension')) . ')$/';

        $rules = [
            'status' => 'required|boolean',
            'slug' =>   'required|unique:categories,slug',
            'position' => 'required|integer',
            'image' => ['regex:' . $regex]
        ];

        $languageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale . '.' . $name] = $rule;
            }
        }

        return $rules;
    }
}
