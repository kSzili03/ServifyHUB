<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Ensure the user is authenticated before making the request
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->route()->getName()) {
            // Validation rules for storing a message
            case 'messages.store':
                return $this->createMessageRules();

            // Validation rules for replying to a message
            case 'messages.reply':
                return $this->replyMessageRules();

            // Validation rules for updating a message
            case 'messages.update':
                return $this->updateMessageRules();

            // Default case returns an empty array if no rules match
            default:
                return [];
        }
    }

    /**
     * Get the validation rules for creating a new message.
     *
     * @return array
     */
    private function createMessageRules(): array
    {
        return [
            // Ensure the receiver ID is required, exists in the users table, and is not the current user
            'receiver_id' => 'required|exists:users,id|not_in:' . auth()->id(),

            // Ensure the subject is required, a valid string, and no more than 100 characters
            'subject' => 'required|string|max:100',

            // Ensure the message content is required and a valid string
            'message' => 'required|string',
        ];
    }

    /**
     * Get the validation rules for replying to an existing message.
     *
     * @return array
     */
    private function replyMessageRules(): array
    {
        return [
            // Ensure the message content is required and a valid string
            'message' => 'required|string',
        ];
    }

    /**
     * Get the validation rules for updating an existing message.
     *
     * @return array
     */
    private function updateMessageRules(): array
    {
        return [
            // Ensure the message content is required and a valid string
            'message' => 'required|string',

            // Ensure the subject is optional but, if provided, is a valid string with a max length of 100 characters
            'subject' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Error messages for sending a message
            'receiver_id.required' => 'The receiver field is required.',
            'receiver_id.exists' => 'The selected receiver does not exist.',
            'receiver_id.not_in' => 'You cannot send a message to yourself.',

            'subject.required' => 'The subject field is required.',
            'subject.string' => 'The subject must be a valid text.',
            'subject.max' => 'The subject may not be greater than 100 characters.',

            // Common message rule
            'message.required' => 'The message field is required.',
            'message.string' => 'The message must be a valid text.',
        ];
    }
}
