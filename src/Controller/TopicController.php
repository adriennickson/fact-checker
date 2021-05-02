<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\CommentRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(
        Request $request, 
        TopicRepository $topicRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $em
    ): Response
    {
        $pagination = null;
        $query = $request->query->get('q');

        if ($query && strlen($query)) {
            $pagination = $paginator->paginate(
                $topicRepository->getQueryFindByTitlePart($query), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );
        }else{
            $pagination = $paginator->paginate(
                $em->createQuery("SELECT t FROM App:Topic t"),
                $request->query->getInt('page', 1),
                10
            );
        }
        return $this->render('topic/index.html.twig', [
            'topics' => $pagination,
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
        // TODO: Send mail to autor of topic and autor of parent comment if aplicable     
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

}
