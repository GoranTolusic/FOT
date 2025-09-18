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

        //2. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //3. Get Response from candidate api
        $response = $reqService->postJson('/api/v2/books', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ],
            'json' => $formatedInputs
        ]);

        //4. if status is not successfull throw error
        if ($response['status'] !== 200) throw new \Exception('Unable to create book', $response['status']);

        //5. Redirect to author page (since we don't have single book page)
        return $this->redirectToRoute('get_single_author_page', ['id' => $dtoInputs->author]);
    }
}
