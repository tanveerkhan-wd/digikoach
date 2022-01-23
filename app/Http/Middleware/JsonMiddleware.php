<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class JsonMiddleware
{
    /**
     * The Response Factory our app uses
     *
     * @var ResponseFactory
     */
    protected $factory;

    /**
     * JsonMiddleware constructor.
     * 
     * @param ResponseFactory $factory
     */
    public function __construct(ResponseFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // First, set the header so any other middleware knows we're
        // dealing with a should-be JSON response. 
        $request->headers->set('Accept', 'application/json');

        // Get the response
        /* $response = $next($request);
        if (!$response instanceof JsonResponse) {
            $response_content = mb_convert_encoding($response->content(), 'UTF-8', 'UTF-8');
            $response = $this->factory->json(
                $response->content(),
                $response->status(),
                $response->headers->all(),
                JSON_INVALID_UTF8_IGNORE
            );
        }

        return $response; */
        return $next($request);
    }
}
