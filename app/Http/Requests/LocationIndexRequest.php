<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LocationIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'min_lat' => 'required_with:max_lat|numeric|between:-90,90',
            'max_lat' => 'required_with:min_lat|numeric|between:-90,90',
            'min_lng' => 'required_with:max_lng|numeric|between:-180,180',
            'max_lng' => 'required_with:min_lng|numeric|between:-180,180',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()
                ->json([
                    'errors' => $validator->errors()
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
