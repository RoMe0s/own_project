<?php

namespace App\Http\Requests\Backend\Menu;

use App\Http\Requests\Request;

class MenuUpdateRequest extends Request
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
        $rules = [
            'show_title'      => 'required|boolean',
            'status'          => 'required|boolean',
            'name'            => 'required',
        ];

        $languageRules = [

        ];

        foreach (config('app.locales') as $locale) {
            foreach ($languageRules as $name => $rule) {
                $rules[$locale.'.'.$name] = $rule;
            }
        }

        $items_rules = [
            'items.new.*.link'     => 'required',
            'items.new.*.status'   => 'required|boolean',
            'items.new.*.position' => 'required|integer',
            'items.old.*.link'     => 'required',
            'items.old.*.status'   => 'required|boolean',
            'items.old.*.position' => 'required|integer',
        ];

        $itemsLanguageRules = [
            'name' => 'required',
        ];

        foreach (config('app.locales') as $locale) {
            foreach ($itemsLanguageRules as $name => $rule) {
                $items_rules['items.new.*.'.$locale.'.'.$name] = $rule;
                $items_rules['items.old.*.'.$locale.'.'.$name] = $rule;
            }
        }

        return array_merge($rules, $items_rules);
    }
}
