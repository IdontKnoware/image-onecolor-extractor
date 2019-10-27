<?php

namespace App\Http\Middleware;

use Closure;

class CheckImagesColorsDir
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
        // Empties out storage directory before storage new files
        if( count( scandir( config( 'filesystems.imagescolors' ) ) ) > 1 ) {
            array_map('unlink', glob(config( 'filesystems.imagescolors' )."/*.*" ));
        }

        return $next($request);
    }
}
