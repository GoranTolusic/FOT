<?php

namespace App\Controller\Book;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\HttpService;

use App\Dto\CreateBookDto;
use App\Traits\RequestValidationTrait;

class AddBookController extends AbstractController
{
    use RequestValidationTrait;

    #[Route('/book/create', name: 'book_create', methods: ['POST'])]
    public function createBook(Request $request, HttpService $reqService): Response
    {
        //1. Validate, sanitize and format inputs from form
        $dtoInputs = $this->validateRequestDto($request->request->all(), CreateBookDto::class);
        $formatedInputs = $dtoInputs->formatToArray();

        //1. Retrieve session and access token from it
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        //If access token is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        //2. Get Response from candidate api
        $response = $reqService->postJson('/api/v2/books', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ],
            'json' => $formatedInputs
        ]);

        //3. if status is not successfull throw error
        if ($response['status'] !== 200) throw new \Exception('Unable to create book', $response['status']);

        //4. Redirect back to previous page
        $referer = $request->headers->get('referer');
        if ($referer) return $this->redirect($referer);

        //5. Fallback if referer does not exists
        return $this->redirectToRoute('get_home_page');
    }
}
