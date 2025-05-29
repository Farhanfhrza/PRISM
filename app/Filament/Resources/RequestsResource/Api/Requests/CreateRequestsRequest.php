<?php

namespace App\Filament\Resources\RequestsResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestsRequest extends FormRequest
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
			'employee_id' => 'required',
			'initiated_by' => 'required',
			'submit' => 'required',
			'approved' => ['nullable', 'date'],
			'status' => 'required',
			'information' => 'required|string',
		];
    }
}
