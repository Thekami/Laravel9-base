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
	 * Returns an array with the custom validation messages
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
	        'required'  			=>  'This field is required',
	        'max'       			=>  'The max length is :max characters long',
	        'min'	        		=>  'The min length is :min characters long',
	        'email'     			=>  'The email address is not validd',
	        'boolean'   			=>  'The possible values are true or false',
	        'valid_zipcode' 		=>  'Invalid zip code',
	        'in'       				=>  'The value is invalid',
			'valid_address_type' 	=>	'Invalid object type',
			'exp_month.max'			=>	'The given value is not valid',
			'required_if' 			=>  'The :attribute is needed when :other is :value',
			'unique_primary_address' => 'Cannot have more than one primary address. Update or delete existing one.',
			'unique_fiscal_address' => 'Cannot have more than one fiscal address. Update or delete existing one.',
			'unique_type_address'	=> 'Cannot have more than one address of this type',
			'greater_than_zero'		=> 'The input has to be greater than zero',
			'valid_password'		=> 'The password is incorrect',
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