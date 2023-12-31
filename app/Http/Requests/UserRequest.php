<?php

namespace App\Http\Requests;

use App\Http\Controllers\API\ResponseController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
    * Indicates if the validator should stop on the first rule failure.
    *
    * @var bool
    */
    protected $stopOnFirstFailure = true;

    /**
     * Custom response message
     * @return JsonResponse
     */
    public function failedValidation(Validator $validator): JsonResponse
    {
        $response = ResponseController::response(false, $validator->errors()->all()[0], Response::HTTP_BAD_REQUEST);
        throw new ValidationException($validator, $response);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->routeIs('register')) {
            return [
                'email'=>"required|email|unique:users,email",
                'first_name'=>"required",
                'last_name'=>"required",
                'phone_number'=>"required|min:11unique:users,phone_number",
                'role'=>"required|in:user,rider",
                'password'=>"required|min:6|max:50",
            ];
        }
        if ($this->routeIs('login')) {
            return [
                'email'=>"required|email",
                'password'=>"required|min:6|max:50",
            ];
        }
        return [
            //
        ];
    }
}
