<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    #[Route('/', name: 'get_home_page', methods: ['GET'])]
    public function getHomePage(Request $request): Response
    {
        //This needs to be some kind of global middleware
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        //If access token is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        $userFirstName = $session->get('user')['first_name'];
        $userLastName = $session->get('user')['last_name'];
        return $this->render('home.html.twig', [
            'message' => "You are logged in. Welcome to Home Page, dear $userFirstName $userLastName!",
        ]);
    }

    #[Route('/login', name: 'get_login_page', methods: ['GET'])]
    public function getLoginPage(Request $request): Response
    {
        $accessToken = $request->getSession()->get('access_token');
        if ($accessToken) return $this->redirectToRoute('get_home_page');

        return $this->render('login.html.twig', [
            'message' => 'Please enter your email and password to login!',
        ]);
    }
}
