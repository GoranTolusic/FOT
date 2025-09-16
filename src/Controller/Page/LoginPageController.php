<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LoginPageController extends AbstractController
{
    #[Route('/login', name: 'get_login_page', methods: ['GET'])]
    public function getLoginPage(Request $request): Response
    {
        //1. If user is already set in server session we are redirecting to home page
        $user = $request->getSession()->get('user');
        if ($user) return $this->redirectToRoute('get_home_page');

        //2. Return rendered login page html
        return $this->render('login.html.twig', [
            'message' => 'Please enter your email and password to login!',
            'defaultEmail' => $_ENV['API_USER_EMAIL'] ?? getenv('API_USER_EMAIL'),
            'defaultPassword' => $_ENV['API_USER_PASSWORD'] ?? getenv('API_USER_PASSWORD')
        ]);
    }
}
