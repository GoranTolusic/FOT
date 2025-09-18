<?php

namespace App\Controller\Page;

use App\Services\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Dto\AuthorsDto;
use App\Traits\RequestValidationTrait;

class AuthorsPageController extends AbstractController
{
    use RequestValidationTrait;

    #[Route('/authors', name: 'get_authors_page', methods: ['GET'])]
    public function getAuthorsPage(Request $request, HttpService $reqService): Response
    {
        //1. Validate and sanitize inputs from query params, and set valid dto inputs
        $dtoInputs = $this->validateRequestDto($request->query->all(), AuthorsDto::class);
        
        //2. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //3. Get response from candidate api
        $response = $reqService->getJson('/api/v2/authors', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ],
            'query' => [
                'page' => $dtoInputs->page,
                'limit' => $dtoInputs->limit,
                'direction' => $dtoInputs->direction,
                'orderBy' => $dtoInputs->orderBy,
                'query' => $dtoInputs->query
            ]
        ]);

        //4. set some error variables if something goes wrong
        $err = false;
        if ($response['status'] !== 200) $err = 'Error occured. Unable to retrieve authors data';
        
        //5. Return rendered html
        return $this->render('authors.html.twig', [
            'err' => $err,
            'query' => $dtoInputs->query,
            'authors' => $response['body']['items'],
            'current_page' => $response['body']['current_page'],
            'direction' => $dtoInputs->direction,
            'orderBy' => $dtoInputs->orderBy,
            'total_results' => $response['body']['total_results'],
            'total_pages' => $response['body']['total_pages'],
            'show_next_page' => $response['body']['current_page'] >= $response['body']['total_pages'] ? false : true,
            'show_previous_page' => $response['body']['current_page'] > "1" ? true : false
        ]);
    }
}
