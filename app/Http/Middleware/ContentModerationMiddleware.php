<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ContentModerationService;

class ContentModerationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Campos que deben ser moderados
        $fieldsToModerate = [
            // Para playlists
            'name',
            'description',
            // Para posts
            'title',
            'content',
            // Para comentarios
            'text'
        ];

        // Aplicar moderación a los campos relevantes en la request
        foreach ($fieldsToModerate as $field) {
            if ($request->has($field)) {
                $originalValue = $request->input($field);
                $moderatedValue = ContentModerationService::moderateContent($originalValue);
                
                // Si el contenido fue modificado, actualizar la request
                if ($originalValue !== $moderatedValue) {
                    $request->merge([$field => $moderatedValue]);
                    
                    // Opcionalmente, agregar una notificación flash
                    if ($request->hasSession()) {
                        $request->session()->flash('content_moderated', 
                            'Tu contenido ha sido moderado automáticamente por contener lenguaje inapropiado.');
                    }
                }
            }
        }

        return $next($request);
    }
}
