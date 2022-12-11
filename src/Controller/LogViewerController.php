<?php

namespace App\Controller;

use App\Service\LogHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogViewerController extends AbstractController
{

    #[Route('/log', name: 'app_log_viewer')]
    public function index(LogHelper $logHelper): Response
    {
        return $this->render('log_viewer/index.html.twig');
    }

    #[Route('/getAction', name: 'app_log_action')]
    public function getAction(Request $request, LogHelper $logHelper): Response
    {
        try {
            $path = $request->request->get('path');

            $path = filter_var($path, FILTER_SANITIZE_SPECIAL_CHARS);
            if(!$path){
                throw new RuntimeException("Path Not Valid",'301');
            }
            
            $type = $request->request->get('type')??'default';

            $type = filter_var($type, FILTER_SANITIZE_SPECIAL_CHARS);
            if(!$type){
                throw new RuntimeException("Type Not Valid",'301');
            }
            
            $page = $request->request->get('page')??1;            
    
            $response = $logHelper->getAction($path, $type, $page);
            return new JsonResponse($response);
        }
        catch (RuntimeException $e){
            return new JsonResponse([ 'status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode());
        }  
    }
}
