<?php

namespace App\Controller\Author;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\HttpService;

class DeleteAuthorController extends AbstractController
{
    #[Route('/author/delete/{id}', name: 'author_delete', methods: ['POST'])]
    public function deleteAuthor(Request $request, HttpService $reqService, int $id): Response
    {
        //1. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //2. Get user response from candidate api
        $authorResp = $reqService->getJson('/api/v2/authors/'.$id, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //Throw exception if author is not retrieved or if books is not empty
        if ($authorResp['status'] !== 200 || !empty($authorResp['body']['books'])) 
            throw new \Exception('Unable to delete author', 409);

        //3. Get delete response from candidate api
        $response = $reqService->deleteJson('/api/v2/authors/' . $id, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //4. if status is not successfull throw error
        if ($response['status'] !== 204) throw new \Exception('Unable to delete author', $response['status']);

        //5. Redirect user to authors page
        return $this->redirectToRoute('get_authors_page');
    }
}
