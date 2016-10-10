<?php

namespace App\Http\Middleware;

use Closure;

class MaxUploadedFileSize
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
        /*
        |--------------------------------------------------------------------------
        | Max Upload File Size filter
        |--------------------------------------------------------------------------
        |
        | Check if a user uploaded a file larger than the max size limit.
        | This filter is used when we also use a CSRF filter and don't want
        | to get a TokenMismatchException due to $_POST and $_GET being cleared.
        |
        */
        // Check if upload has exceeded max size limit
        if (! ($request->isMethod('POST') or $request->isMethod('PUT'))) { return; }
        // Get the max upload size (in Mb, so convert it to bytes)
        $maxUploadSize = 1024 * 1024 * ini_get('post_max_size');        
        $contentSize = 0;
        if (isset($_SERVER['HTTP_CONTENT_LENGTH']))
        {
            $contentSize = $_SERVER['HTTP_CONTENT_LENGTH'];
        } 
        elseif (isset($_SERVER['CONTENT_LENGTH']))
        {
            $contentSize = $_SERVER['CONTENT_LENGTH'];
        }
        // If content exceeds max size, throw an exception
        if ($contentSize > $maxUploadSize)
        {
            throw new GSVnet\Core\Exceptions\MaxUploadSizeException;
        }

        return $next($request);
    }
}
