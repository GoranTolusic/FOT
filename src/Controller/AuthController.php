<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = $request->request->all();
        consoleLog($data);
        $session = $request->getSession();
        //First invalidate existing one
        $session->invalidate();

        //TODO: Validate input, and proceed params to service to handle api call to candidate API and retrieve token, set token in session

        //Set session
        $session->set('access_token', '1234');
        return $this->redirectToRoute('get_home_page');
    }

    #[Route('/auth/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->invalidate();
        return $this->redirectToRoute('get_home_page');
    }
}
