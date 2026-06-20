<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255', 'unique:posts,title'],
            'slug' => ['nullable', 'alpha_dash', 'unique:posts,slug'],
            'content' => ['required', 'string', 'min:50'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок не может быть пустым',
            'title.min' => 'Заголовок должен содержать минимум :min символов',
            'title.unique' => 'Пост с таким заголовком уже существует',
            'content.required' => 'Содержание поста обязательно',
            'content.min' => 'Содержание должно быть не короче :min символов',
            'published_at.after' => 'Дата публикации должна быть в будущем',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'заголовок',
            'content' => 'содержание',
            'published_at' => 'дата публикации',
        ];
    }
}
