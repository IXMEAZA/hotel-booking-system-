<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
        'check_in_date' => ['required','date'],
        'check_out_date' => ['required','date','after:check_in_date'],
        'adults_count' => ['required','integer','min:1','max:20'],
        'children_count' => ['nullable','integer','min:0','max:20'],
        'room_type_id' => ['required','exists:room_types,id'],
        'room_ids' => ['required','array','min:1'],
        'room_ids.*' => ['required','exists:rooms,id'],
        'notes' => ['nullable','string','max:2000'],
        'paid_amount' => ['nullable', 'numeric', 'min:0'],
        'payment_method' => ['required', 'in:cash,card'],
        'guest_name' => ['required', 'string', 'max:255'],
        'guest_phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
    ];
}

        
    }

