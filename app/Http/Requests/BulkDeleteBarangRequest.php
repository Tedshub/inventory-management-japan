<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteBarangRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:barangs,id'],
        ];
    }
}
