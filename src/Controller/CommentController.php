<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments", name="comments")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repository->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/comment/add", name="addComment")
     */
    public function addComment(Request $request)
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $comment = $form->getData();

            

        }

    }

}
