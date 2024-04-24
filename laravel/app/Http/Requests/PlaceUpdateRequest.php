<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Handle authorization logic for the request 
        // in another part of your application
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'upload'      => 'nullable|mimes:gif,jpeg,jpg,png,mp4|max:2048',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'visibility'  => 'nullable|integer|exists:visibilities,id',

        ];
    }
}
