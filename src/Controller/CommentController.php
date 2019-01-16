<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repository->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
