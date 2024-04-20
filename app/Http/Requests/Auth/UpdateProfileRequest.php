<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'required',
            'phone' => [
                'required',
                Rule::unique('users')->ignore($this->user)->where(function ($query) {
                    return $query->where('phone', '!=', null);
                }),
                'digits:10',
            ],
            'gender' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,PNG,jpec',
            'password' => 'nullable|min:6',
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response([
            'message' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw (new ValidationException($validator, $response));
    }
}
