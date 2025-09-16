<?php

namespace App\Controller\Book;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\HttpService;

class DeleteBookController extends AbstractController
{
    #[Route('/book/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function deleteBook(Request $request, HttpService $reqService, int $id): Response
    {
        //1. Retrieve session and access token from it
        $session = $request->getSession();
        $accessToken = $session->get('access_token');
        //If access token is missing from session we are assuming that session is invalidated so we are redirecting to login page
        if (!$accessToken) return $this->redirectToRoute('get_login_page');

        //2. Get Response from candidate api
        $response = $reqService->deleteJson('/api/v2/books/' . $id, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //3. if status is not successfull throw error
        if ($response['status'] !== 204) throw new \Exception('Unable to delete book', $response['status']);

        //4. Redirect back to previous page
        $referer = $request->headers->get('referer');
        if ($referer) return $this->redirect($referer);

        //5. Fallback if referer does not exists
        return $this->redirectToRoute('get_home_page');
    }
}
