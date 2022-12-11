<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $username = filter_var($request->request->get('username'), FILTER_SANITIZE_STRING);
        if(!$username){
            return new JsonResponse(['status' => 205, 'message' => 'Enter Valid credentials']);
        }

        $password = filter_var($request->request->get('password'), FILTER_SANITIZE_STRING);
        if(!$password){
            return new JsonResponse(['status' => 205, 'message' => 'Enter Valid credentials']);
        }        

        if($username === 'admin' && $password === 'admin'){
            return new JsonResponse(['status' => 200, 'message' => 'Your login is successfull']);
        }else{
            return new JsonResponse(['status' => 205, 'message' => 'Wrong credentials']);
        }
    }    
}
