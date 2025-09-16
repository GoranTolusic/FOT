<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\LoginDto;
use App\Services\HttpService;
use App\Traits\RequestValidationTrait;

class LoginController extends AbstractController
{
    use RequestValidationTrait;

    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request, HttpService $reqService): Response
    {
        //1. Validate and sanitize inputs from form
        $dtoInputs = $this->validateRequestDto($request->request->all(), LoginDto::class);

        //2. Get Response from candidate api
        $response = $reqService->postJson('/api/v2/token', [
            'json' => [
                "email" => $dtoInputs->email,
                "password" => $dtoInputs->password,
            ]
        ]);

        //3. If token is not retrieved redirect user to login page with error message
        if ($response['status'] !== 200) return $this->render('login.html.twig', [
                'message' => 'Please enter your email and password to login!',
                'errormsg' => 'Invalid Credentials'
            ]);
        

        //4. If response is successfull, we are seting token and user data to server session
        $session = $request->getSession();
        $session->set('access_token', $response['body']['token_key']);
        $session->set('user', $response['body']['user']);

        //5. After session is saved, redirect user to home page
        return $this->redirectToRoute('get_home_page');
    }
}
