<?php

namespace App\Controller\Page;

use App\Services\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorsPageController extends AbstractController
{
    #[Route('/authors', name: 'get_authors_page', methods: ['GET'])]
    public function getAuthorsPage(Request $request, HttpService $reqService): Response
    {
        //1. Retrieve session and access token from it
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        //If access token is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        //HINT: I can retrieve data from user session, but i realized 
        //there is "me" endpoint in API docs for retrieving info about logged user

        //2. Get response from candidate api
        $response = $reqService->getJson('/api/v2/authors', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ],
            'query' => [
                'page' => '2',
                'limit' => '12',
                'direction' => 'ASC',
                'orderBy' => 'id'
            ]
        ]);

        //3. set some error variables if something goes wrong
        $err = false;
        if ($response['status'] !== 200) {
            $err = 'Error occured. Unable to retrieve authors data';
        }

        consoleLog($response['body']);

        //4. Return rendered my profile data
        return $this->render('authors.html.twig', [
            'err' => $err,
            'authors' => $response['body']
        ]);
    }
}
