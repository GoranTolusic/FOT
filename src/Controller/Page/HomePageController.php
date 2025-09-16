<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'get_home_page', methods: ['GET'])]
    public function getHomePage(Request $request): Response
    {
        //1. Retrieve user from server session
        $session = $request->getSession();
        $user = $session->get('user');
        //If user is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$user) return $this->redirectToRoute('get_login_page');

        //2. Set some dynamic user data for greetings message
        $userFirstName = $user['first_name'];
        $userLastName = $user['last_name'];

        //3. Redirect to home page with personal greeting
        return $this->render('home.html.twig', [
            'message' => "You are logged in. Welcome to Home Page, dear $userFirstName $userLastName!",
        ]);
    }
}
