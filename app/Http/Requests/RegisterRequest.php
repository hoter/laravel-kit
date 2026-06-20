<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50', 'alpha_num'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'min:50'],
            'website' => ['nullable', 'url'],
            'agreement' => ['required'],
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
            'name' => 'имя',
            'email' => 'электронная почта',
            'password' => 'пароль',
            'phone' => 'телефон',
            'website' => 'сайт',
            'agreement' => 'соглашение',
        ];
    }
}
