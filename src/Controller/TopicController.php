<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\CommentRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{
    /**
     * @Route("/", name="topic_index", methods={"GET"})
     */
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="topic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $topic->setAutor($this->getUser());
            $topic->setCreatedAt(new \DateTime());
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="topic_show", methods={"GET"})
     */
    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="topic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Topic $topic): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() != $topic->getAutor()->getId()) {
            return $this->redirectToRoute('topic_index');
        }
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="topic_delete", methods={"POST"})
     */
    public function delete(Request $request, Topic $topic): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() != $topic->getAutor()->getId()) {
            return $this->redirectToRoute('topic_index');
        }
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('topic_index');
    }

    /**
     * @Route("/{id}/comment", name="topic_comment", methods={"POST"})
     */
    public function comment(Request $request, Topic $topic, CommentRepository $commentRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $comment = new Comment();
        $comment->setTopic($topic);
        if ($request->request->get('parent')) {
            $comment->setParent($commentRepository->find($request->request->get('parent')));
        }
        $comment->setContent($request->request->get('content'));
        $comment->setCreatedAt(new \DateTime());
        $comment->setAutor($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();    
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

}
