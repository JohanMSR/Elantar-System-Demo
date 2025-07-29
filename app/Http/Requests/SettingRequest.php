<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class SettingRequest extends FormRequest
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
            'phone' => 'required|max:18|regex:/^\+?([0-9]{1,3})?\s?\(?([0-9]{3})\)?[- ]?([0-9]{3})[- ]?([0-9]{4})$/',
            'first_location' => 'nullable|string|max:255',
            'second_location' => 'nullable|string|max:255',
            'image_profile' => 'exclude_if:image_profile,null|image|min:1|max:3072|mimes:jpeg,jpg,gif,png,webp'            
            //
        ];
    }
}
