<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends AbstractController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        //1. Load server session for user and invalidate it
        $session = $request->getSession();
        $session->invalidate();

        //2. Redirect user to home page
        return $this->redirectToRoute('get_home_page');
    }
}
