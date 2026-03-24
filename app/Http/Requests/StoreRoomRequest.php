<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
        'room_number' => ['required','string','max:20','unique:rooms,room_number'],
        'room_type_id' => ['required','exists:room_types,id'],
        'floor' => ['nullable','integer'],
        'status' => ['required','in:available,occupied,maintenance'],
        'is_active' => ['nullable','boolean'],
        ];
    }
}
