<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAuditRequest extends FormRequest
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
            'date' => 'required|date',
            'mode' => 'required|integer|min:0|max:2',
            'site_id' => 'required|integer|exists:sites,id',
            'contact' => 'required|integer|exists:users,id',
            'is_virtual' => 'integer|nullable',
            'comment' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ];
    }
}
