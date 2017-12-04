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
        'email' => [
            'email' => ' El campo :attribute debe tener un formato válido de correo',
            'required'=>' El campo :attribute es requerido',
            'min'=>' El campo :attribute debe tener al menos :min caracteres',
        ],
        'username' => [
            'required'=>' El usuario es requerido',
            'min'=>' El usuario debe tener al menos :min caracteres'
        ],
        'password' => [
            'required'=>' El campo :attribute es requerido',
            'min'=>' El campo :attribute debe tener al menos :min caracteres',
            'max'=>' El campo :attribute debe tener como máximo :max caracteres',
        ],
        'id' => [
            'required'=>' El ID es necesario',
            'min'=>' El ID debe ser numérico',
            'numeric'=>' El ID debe tener al menos :min caracteres',
        ],
        'value' => [
            'required'=>' El valor es necesario',
            'numeric'=>' El valor debe ser numérico',
        ],
        'quantity' => [
            'required'=>' La cantidad es necesaria',
            'numeric'=>' La cantidad debe ser numérica',
        ],
        'first_name' => [
            'required'=>'El campo :attribute es requerido',
            'min'=>' El campo :attribute debe tener al menos :min caracteres.'
        ],
        'last_name' => [
            'required'=>'El campo :attribute es requerido',
            'min'=>' El campo :attribute debe tener al menost :min caracteres.'
        ],
        'full_name' => [
            'required'=>' El campo :attribute es requerido',
            'min'=>' El campo :attribute debe tener al menos :min caracteres'
        ],
        'job_title' => [
          'required'=>' El campo :attribute es requerido',
          'min'=>' El campo :attribute debe tener al menos :min caracteres'
        ],
        'company_name' => [
          'required'=>' El campo :attribute es requerido',
          'min'=>' El campo :attribute debe tener al menos :min caracteres'
        ],
        'job_description' => [
          'required'=>' El campo :attribute es requerido',
          'min'=>' El campo :attribute debe tener al menos :min caracteres'
        ],
        'address' => [
          'required'=>' La dirección es requerida',
          'min'=>' La dirección debe tener al menos :min caracteres',
        ],
        'contact_name' => [
          'required'=>' EL nombre de contacto es requerida',
          'min'=>' El nombre de contacto debe tener al menos :min caracteres',
        ],
        'phone_number' => [
          'required'=>' El número telefónico es necesario',
          'numeric'=>' El número telefónico debe ser numérico',
          'min'=>' El número telefónico debe tener al menos :min caracteres',
          'max'=>' El número telefónico debe tener como máximo :max caracteres',
        ],
        'name' => [
          'required'=>' El nombre es requerido',
          'min'=>' El nombre debe tener al menos :min caracteres',
          'max'=>' El nombre debe tener como máximo :max caracteres'
        ],
        'friend_1' => [
          'required'=>' El contacto 1 requerido'
        ],
        'friend_2' => [
          'required'=>' El contacto 2 requerido'
        ],
        'question_friend_1' => [
          'required'=>' La pregunta del contacto 1  es requerida',
          'min'=>' La pregunta del contacto 1 debe tener al menos :min caracteres'
        ],
        'question_friend_2' => [
          'required'=>' La pregunta del contacto 2  es requerida',
          'min'=>' La pregunta del contacto 2 debe tener al menos :min caracteres'
        ],
        'reason' => [
          'required'=>' La razón de la intro es requerida',
          'min'=>' La razón de la intro debe tener al menos :min caracteres'
        ],
        'message' => [
          'required'=>' El mensaje es requerido',
          'min'=>' El mensaje debe tener al menos :min caracteres'
        ]
    ],
    'messages' => [
      'not_direction' => 'La dirección no fue encontrada',
      'not_direction_ajax' => 'La dirección no fue encontrada en la solicitud AJAX',
      'not_session' =>'Session no encontrada',
      'invalid_values' =>'El usuario o la contraseña es incorrecto',
      'session_impossible' =>'No se pudo iniciar sesión',
      'register_session_impossible' =>'No es posible registrarse en estos momentos',
      'not_token_found' => 'No se ha encontrado el token',
      'not_token' => 'No hay token para retornar',
      'token_expired' => 'Token vencido',
      'token_invalid' => 'Token inválido',
      'not_type_user' => 'No hay tipo de usuario',
      'invalid_number' => 'El número tiene formato inválido',
      'email_exist' => 'El correo electrónico ya se encuentra registrado en Intro App',
      'email_no_exist' => 'El correo electrónico no se encuentra registrado en Intro App',
      'username_exist' => 'El usuario ya se encuentra registrado en Intro App',
      'register_send_email' => 'Registro en Intro App',
      'update_send_email' => 'Actualización de datos en Intro App',
      'fail_send_email' => 'El correo no pudo ser enviado',
      'success_register' => 'Registro exitoso',
      'dear_email' => 'Estimado(a)',
      'success_register_email' => 'Usted se ha registrado en Intro App satisfactoriamente',
      'success_update_email' => 'Usted ha actualizado sus datos en Intro App satisfactoriamente',
      'context_send_email' => 'Usted ha iniciado el proceso para resetear su clave, para continuar haga click sobre el siguiente enlace desde su dispositivo móvil o tablet',
      'success_remember_email' => 'Se ha enviado a su correo electrónico la instrucciones para recordar su clave',
      'remember_password' => 'Recordar clave',
      'remember_password_send_mail' => 'Recordar clave en intro App',
      'your_information_email' => 'Estos son sus datos',
      'regards_email' => 'Saludos',
      'user_email' => 'Usuario',
      'name_label' => 'Nombre',
      'email_label' => 'Correo',
      'password_email' => 'Clave',
      'password_not_match' => 'La clave indicada no coincide con su clave actual',
      'number_phones_email' => 'Números telefónicos',
      'user_not_found' => 'No se ha encontrado al usuario',
      'success_update' => 'Actualización exitosa',
      'success_forgot_password' => 'El Correo para resetear password ha sido enviado',
      'forgot_password_sent' => 'El Correo para resetear password ha sido enviado previamente',
      'success_delete' => 'Eliminación exitosa',
      'not_permission' => 'El usuario no posee los permisos necesarios para acceder a la sección solicitada',
      'not_external_login' => 'Este usuario no puede hacer login por esta plataforma',
      'login_by_external_facebook' => 'Este usuario solo puede hacer login por Facebook',
      'login_by_external_linkedin' => 'Este usuario solo puede hacer login por Linkedin',
      'login_by_external_google_plus' => 'Este usuario solo puede hacer login por Google+',
      'image_invalid_format' => 'Imagen con formato inválido',
      'invitation_not_found' => 'Invitación no encontrada',
      'invitation_accept_valid' => 'La invitación ha sido aceptada, no puede ser procesada',
      'invitation_accept_invalid' => 'La invitación ha sido rechazada, no puede ser procesada',
      'is_contact' => 'Este usuario ya es un contacto',
      'is_contact_pending' => 'Ya has enviado una invitación a esta persona',
      'content_invitation' => 'te ha enviado una invitación en Intro Pro, haz clic en el siguiente enlace para aceptar',
      'invitation_send_email' => 'Invitación en Intro App',
      'accept_invitation' => 'Acceptar Invitación',
      'invalid_user' =>'Usuario inválido',
      'user_1_not_exist' =>'El contacto 1 no existe',
      'user_not_exist' =>'El usuario no existe',
      'user_2_not_exist' =>'El contacto 2 no existe',
      'intros_exists' =>'Los contactos han sido presentados anteriormente',
      'invitation_email' => 'Invitación de contacto in Intro App',
      'success_invitation_email' =>' te ha enviado una invitación para que entres en contacto con ',
      'success_invitation_final_email' =>' Haz clic sobre el siguiente enlace para aceptar la invitación',
      'both_contacts' =>' Los contactos seleccionados ya han sido introducidos previamente.',
      'contact_not_found' =>' Contacto no encontrado',
      'friend_found' => 'El contacto está vinculado a una introducción, no se puede eliminar',
      'not_access_chat' => 'Usted no tiene permisos para acceder a este chat',
      'not_room' => 'El chat no fue encontrado',
      'intro_abandoned' => 'Intro abandonada',
      'contact_us_email_init' => 'El usuario',
      'contact_us_email_end' => 'ha enviado la siguiente el siguiente mensaje:',
      'subject_email_contact_us' => 'INTRO PRO: Mensaje enviado de',
      'success_contact_us' => 'Mensaje enviado de forma exitosa',
      'email_no_exist_contact_us' => 'El correo para enviar los mensajes no existe en Intro App',
      'must_complete_fields' => 'Debes completar los campos para poder continuar',
      'success_reset_password' =>'El password ha sido cambiado satisfactoriamente',
      'confirm_password_match_error'=> 'La clave y la confirmación no coinciden.'
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
