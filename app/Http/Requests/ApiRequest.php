<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends Request
{
	const HTTP_UNPROCESSABLE_ENTITY = 422;
	const ERROR_TYPE = 'unprocessable_entity';
	const ERROR_MSG = ' La solicitud no puede ser procesada porque los datos son inválidos.';

	/**
	 * Sanitizes data before validation rules are executed
	 *
	 * @return array
	 */
	public function sanitize()
	{
		$input['user_id'] = !is_null(\Auth::user()) ? \Auth::user()->id : null;
        $this->merge($input);

        return $this->all();
	}

    protected function formatErrors(Validator $validator)
	{
		return [
            'success' => false,
            'message' => self::ERROR_MSG,
            'error' => $validator->errors(),
        ];
	}

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response($this->formatErrors($validator), self::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}