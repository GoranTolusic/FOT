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
    public function getSingleAuthorPage(Request $request, HttpService $reqService, int $id): Response
    {
        //1. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //2. Get response from candidate api
        $response = $reqService->getJson('/api/v2/authors/'.$id, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //3. set some error variables if something goes wrong
        $err = false;
        if ($response['status'] !== 200) $err = 'Error occured. Unable to retrieve author data';
        
        //4. Return rendered html
        return $this->render('author.html.twig', [
            'err' => $err,
            'user' => $response['body']
        ]);
    }
}
