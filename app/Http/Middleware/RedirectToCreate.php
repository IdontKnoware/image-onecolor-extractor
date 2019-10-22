<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->getRequestUri() != '/public/images'){
            redirect()->action('ImageColorExtractorController@create');
        }
        return $next($request);
    }
}
