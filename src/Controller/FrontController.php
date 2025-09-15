<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends AbstractController
{
    #[Route('/', name: 'get_home_page', methods: ['GET'])]
    public function getHomePage(Request $request): Response
    {
        //This needs to be some kind of global middleware
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        return $this->render('base.html.twig', [
            'message' => 'Hello world',
        ]);
    }

    #[Route('/login', name: 'get_login_page', methods: ['GET'])]
    public function getLoginPage(Request $request): Response
    {
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        if ($accessToken) return $this->redirectToRoute('get_home_page');

        return $this->render('login.html.twig', [
            'message' => 'Please enter your email and password to login!',
        ]);
    }
}
