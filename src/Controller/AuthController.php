<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\LoginDto;
use App\Services\HttpService;
use App\Traits\RequestValidationTrait;

class AuthController extends AbstractController
{
    use RequestValidationTrait;

    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request, HttpService $reqService): Response
    {
        $dtoInputs = $this->validateRequestDto($request, LoginDto::class);
        $payload = [
            "email" => $dtoInputs->email,
            "password" => $dtoInputs->password,
        ];

        $response = $reqService->postJson('/api/v2/token', ['json' => $payload]);
        if ($response['status'] !== 200) {
            return $this->render('login.html.twig', [
                'message' => 'Please enter your email and password to login!',
                'errormsg' => 'Invalid Credentials'
            ]);
        }

        $session = $request->getSession();
        $session->set('access_token', $response['body']['token_key']);
        $session->set('user', $response['body']['user']);

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
