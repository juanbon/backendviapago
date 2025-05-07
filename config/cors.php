<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', '*'],

    'allowed_methods' => ['*'], // Permitir todos los métodos (GET, POST, PUT, DELETE)

    'allowed_origins' => ['http://localhost:4200'], // Acepta solo el frontend Angular en localhost

    'allowed_origins_patterns' => [],

    //  'allowed_headers' => ['*'], // Permitir todos los headers

    'allowed_headers' => ['*', 'Authorization', 'Content-Type', 'X-Requested-With'], // Asegúrate de permitir Authorization


    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // IMPORTANTE si vas a usar cookies o token en headers

];
