<?php

namespace App\Controller\Page;

use App\Services\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SingleAuthorPageController extends AbstractController
{
    #[Route('/author-details/{id}', name: 'get_single_author_page', methods: ['GET'])]
    public function getAuthorsPage(Request $request, HttpService $reqService, int $id): Response
    {
        //1. Retrieve session and access token from it
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        //If access token is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        //3. Get response from candidate api
        $response = $reqService->getJson('/api/v2/authors/'.$id, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //4. set some error variables if something goes wrong
        $err = false;
        if ($response['status'] !== 200) {
            $err = 'Error occured. Unable to retrieve author data';
        }

        //4. Return rendered my profile data
        return $this->render('author.html.twig', [
            'err' => $err,
            'user' => $response['body']
        ]);
    }
}
