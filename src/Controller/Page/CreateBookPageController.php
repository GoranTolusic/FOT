<?php

namespace App\Controller\Page;

use App\Services\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CreateBookPageController extends AbstractController
{
    #[Route('/create-book', name: 'create_book_page', methods: ['GET'])]
    public function getCreateBookPage(Request $request, HttpService $reqService): Response
    {       
        //1. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //2. Fetch authors for dropdown.
        //HINT:  I'm hardcoding 50 limit just for sake of dropdown items. 
        //In real case scenario, implementing dynamic searchable dropdown on client is correct way
        $authorsResp = $reqService->getJson('/api/v2/authors', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ],
            'query' => [
                'page' => '1',
                'limit' => '50',
                'direction' => 'ASC',
                'orderBy' => 'id'
            ]
        ]);

        //3. set some error variables if something goes wrong
        $err = false;
        if ($authorsResp['status'] !== 200) $err = 'Error occured. Unable to retrieve authors data';
        
        //4. Return rendered html
        return $this->render('add-book.html.twig', [
            'err' => $err,
            'authors' => $authorsResp['body']['items']
        ]);
    }
}
