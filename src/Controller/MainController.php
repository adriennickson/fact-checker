<?php

namespace App\Controller;

use App\Service\GoogleCustomSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, GoogleCustomSearch $googleCustomSearch): Response
    {
        $query = $request->query->get('q');
        $googleCustomSearchResult = $googleCustomSearch->request($query);
        dump($googleCustomSearchResult);
        if ($query){
            return $this->render('main/index.html.twig', [
                'data' => [
                    'query' => $query,
                    'google' => $googleCustomSearchResult
                ]
            ]);        
        };

        return $this->render('main/index.html.twig');
    }
}
