<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;

class TokenFromHeader
{


    public function handle($request, Closure $next)
    {
        Log::info('TokenFromHeader middleware ejecutado en ruta: ' . $request->path());
    
        $token = $request->header('x-access-token');
    
        Log::info('Token recibido en el header: ' . $token);
    
        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
    
            if ($accessToken && $accessToken->tokenable) {
                Log::info('Token válido. Usuario asociado: ' . json_encode($accessToken->tokenable));
                $request->setUserResolver(fn () => $accessToken->tokenable);
            } else {
                Log::warning('Token no encontrado o sin usuario asociado.');
            }
        } else {
            Log::warning('No se recibió token en el header.');
        }
    
        return $next($request);
    }
    

/*

    public function handle($request, Closure $next)
    {
        $token = $request->header('x-access-token');

        Log::info('Token recibido en el header: ' . $token);  // Log para verificar el token recibido

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            // Log para verificar si encontramos el token en la base de datos
            if ($accessToken) {
                Log::info('Token encontrado: ', ['token' => $accessToken]);
            } else {
                Log::warning('Token no encontrado en la base de datos.');
            }

            if ($accessToken && $accessToken->tokenable) {
                $user = $accessToken->tokenable;
                Log::info('Usuario autenticado: ', ['user' => $user]);  // Log para verificar el usuario autenticado

                // Establecemos el usuario en el request
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
            } else {
                Log::warning('No se pudo asociar el token con un usuario válido.');
            }
        } else {
            Log::warning('No se proporcionó ningún token en el header.');
        }

        return $next($request);
    }


*/

}




/*
namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;

class TokenFromHeader
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('x-access-token');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken && $accessToken->tokenable) {
                $request->setUserResolver(function () use ($accessToken) {
                    return $accessToken->tokenable;
                });
            }
        }

        return $next($request);
    }
}


*/