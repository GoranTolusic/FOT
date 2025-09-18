<?php

namespace App\Controller\Page;

use App\Services\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MyProfilePageController extends AbstractController
{
    #[Route('/myProfile', name: 'get_my_profile_page', methods: ['GET'])]
    public function getMyProfilePage(Request $request, HttpService $reqService): Response
    {
        //1. Getting access token
        $accessToken = $request->attributes->get('access_token');

        //HINT: I can retrieve data from user session, but i realized 
        //there is "me" endpoint in API docs for retrieving info about logged user

        //2. Get response from candidate api
        $response = $reqService->getJson('/api/v2/me', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json'
            ]
        ]);

        //3. set some error variables if something goes wrong
        $err = false;
        if ($response['status'] !== 200) $err = 'Error occured. Unable to retrieve user data';

        //4. Return rendered my profile data
        return $this->render('my-profile.html.twig', [
            'err' => $err,
            'user' => $response['body']
        ]);
    }
}
