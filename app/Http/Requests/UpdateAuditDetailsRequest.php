<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuditDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $audit = $this->route('audit');

        if (! $audit) {
            return false;
        }

        return $audit->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'good_practice' => 'nullable|boolean',
            'point_of_improvement' => 'nullable|boolean',
            'signature' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^data:image\/(png|jpeg);base64,/', $value)) {
                    $fail('The ' . $attribute . ' must be a valid base64-encoded PNG or JPEG image.');
                    return;
                }
            }],
            'comment' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];
    }
}
