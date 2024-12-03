<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Titlul este obligatoriu.',
            'title.string' => 'Titlul trebuie să fie un șir de caractere.',
            'title.min' => 'Titlul trebuie sa aiba mai mult de 2 caractere.',
            'title.max' => 'Titlul nu poate avea mai mult de 255 de caractere.',
            'description.string' => 'Descrierea trebuie să fie un șir de caractere.',
            'category_id.exists' => 'Categoria selectată nu este validă.',
            'tags.array' => 'Tag-urile trebuie să fie un array.',
            'tags.*.exists' => 'Unul sau mai multe tag-uri selectate nu sunt valide.',
        ];
    }
}
