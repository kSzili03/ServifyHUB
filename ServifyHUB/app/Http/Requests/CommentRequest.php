<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Authorize the request for authenticated users only
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Ensure the comment is required, a string, and no more than 200 characters long
            'comment' => 'required|string|max:200',
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Error messages for the comment field
            'comment.required' => 'The comment field is required.',
            'comment.string' => 'The comment must be a valid string.',
            'comment.max' => 'The comment may not be greater than 200 characters.',
        ];
    }
}
