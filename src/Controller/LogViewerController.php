<?php

namespace App\Controller;

use App\Service\LogHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogViewerController extends AbstractController
{

    #[Route('/log', name: 'app_log_viewer')]
    public function index(Request $request, LogHelper $logHelper): Response
    {
        $path = $request->request->get('path');

        $response = $logHelper->getAction($path, '', 1);
        return new JsonResponse($response);      
    }

    #[Route('/getAction', name: 'app_log_action')]
    public function getAction(Request $request, LogHelper $logHelper): Response
    {
        $path = $request->request->get('path');
        $page = $request->request->get('page');
        $type = $request->request->get('type');

        $response = $logHelper->getAction($path, $type, $page);
        return new JsonResponse($response);
    }
}
