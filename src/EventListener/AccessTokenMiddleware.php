<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

//Middleware for checking if access token exists for user session. 
//If not, we are redirecting him to login page before request reach the controller
class AccessTokenMiddleware
{
    //List of routes which needs to be intercepted by this middleware
    private array $authorizedRoutes = [
        'author_delete',
        'book_create',
        'book_delete',
        'get_authors_page',
        'create_book_page',
        'get_my_profile_page',
        'get_single_author_page'
    ];

    public function __construct(private RouterInterface $router) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        //Get targeted route name and check against authorized routes.
        $route   = $request->attributes->get('_route');
        if (!in_array($route, $this->authorizedRoutes)) return;
        
        //If access token does not exists, we are creating redirect response instance and passing generated url for login page argument
        $accessToken = $request->getSession()?->get('access_token');
        if (!$accessToken) $event->setResponse(new RedirectResponse($this->router->generate('get_login_page')));

        //Even if backend send redirect response this line will be executed, but it will set access token as empty/null for current session so we are good anyway
        $request->attributes->set('access_token', $accessToken);
    }
}
