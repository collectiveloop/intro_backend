<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'var' => [
            'email' => '',
            'required'=>'',
        ],
        'id' => [
            'required'=>'The ID es required',
            'min'=>' The ID must be at least :min 1 character.',
            'numeric'=>' The ID must be numeric.'
        ],
        'first_name' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'last_name' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'email' => [
            'required'=>'The :attribute es required',
            'mail'=>' The :attribute must have a valid format.',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'username' => [
            'required'=>'The username es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'full_name' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'job_title' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'company_name' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'job_description' => [
            'required'=>'The :attribute es required',
            'min'=>' The :attribute must be at least :min characters.'
        ],
        'password' => [
            'required'=>' The password is required',
            'min'=>' The :attribute must have at least :min characters.',
            'max'=>' The :attribute must have as maximum :max characters.',
        ],
    ],
    'messages' => [
      'not_direction' => 'Direction not found',
      'not_direction_ajax' => 'Direction not found in the AJAX request',
      'invalid_values' =>'Invalid values for Login',
      'session_impossible' =>'It\'s not posible log in',
      'not_token' => 'There is not token',
      'token_expired' => 'Token expired',
      'token_invalid' => 'Invalid token',
      'not_type_user' => 'There is not type user',
      'invalid_number' => 'There is an invalid number',
      'email_exist' => 'The email already exists in the platform',
      'username_exist' => 'The username already exists in the platform',
      'register_send_email' => 'Register in It\'s possible',
      'update_send_email' => 'Update in It\'s possible',
      'fail_send_email' => 'The email can not be sent',
      'success_register' => 'Successful registration',
      'dear_email' => 'Estimado(a)',
      'success_register_email' => 'Your are registered in It\'s possible',
      'success_update_email' => 'Your are updated your information in It\'s possible',
      'success_remember_email' => 'your password reminders instructions have been sent to your email',
      'your_information_email' => 'Your information',
      'regards_email' => 'Regards',
      'user_email' => 'User',
      'email_email' => 'Email',
      'password_email' => 'Password',
      'number_phones_email' => 'Number Phones',
      'user_not_found' => 'The user is not been found',
      'success_update' => 'Successful update',
      'success_delete' => 'Successful Elimination',
      'not_permission' => 'The user does not have permission to access in this section',
      'not_external_login' => 'This user can not login by this platform',
      'login_by_external_facebook' => 'This user just can Login by Facebook',
      'login_by_external_linkedin' => 'This user just can Login by Linkedin',
      'login_by_external_google_plus' => 'This user just can Login by Google+',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
