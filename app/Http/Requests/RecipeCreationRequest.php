<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'                  => 'string|required',
            'image'                  => 'image|max:4096|required',
            'time'                   => 'integer|required',
            'steps'                  => 'array|nullable',
            'steps.*.name'           => 'string|required',
            'steps.*.description'    => 'string|required',
            'steps.*.duration'       => 'integer|numeric|required|min:1',
            'ingredients'            => 'array|nullable',
            'ingredients.*.name'     => 'string|required|max:255',
            'ingredients.*.quantity' => 'integer|numeric|required|min:1',
            'ingredients.*.measure'  => 'string|required',
            'tags'                   => 'array|nullable',
            'tags.*'                 => 'string|required'
        ];
    }
}
