<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TopicRepository;
use App\Service\GoogleCustomSearch;
use App\Service\TwitterCustomSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(
        Request $request, 
        GoogleCustomSearch $googleCustomSearch,
        TwitterCustomSearch $twitterCustomSearch,
        TopicRepository $topicRepository
        ): Response
    {
        $query = $request->query->get('q');

        if ($query && strlen($query)){

            //$googleCustomSearchResult = ['error' => ['code' => 9999, 'message'=> 'test']];
            $googleCustomSearchResult = $googleCustomSearch->request($query);
            $twitterCustomSearchResult = $twitterCustomSearch->request($query);
            $topics = $topicRepository->findByTitlePart($query);

            dump($googleCustomSearchResult);

            return $this->render('main/index.html.twig', [
                'data' => [
                    'query' => $query,
                    'google' => $googleCustomSearchResult,
                    'twitter' => $twitterCustomSearchResult,
                    'topics' => $topics
                ]
            ]);        
        };

        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/autor/{id}", name="get_autor", methods={"GET"})
     */
    public function autor(User $user): Response
    {
        foreach ($user->getTopics() as $value) {
            $user->removeTopic($value);
        }
        //$user->setPassword('');
        return $this->json( $user );
    }

}
