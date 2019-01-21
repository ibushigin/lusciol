<?php
namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class Rate{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function rateAverage($address)
    {

        $repo = $this->em->getRepository(Comment::class);
        $comments = $repo->findByAddress($address);
        $allComments = count($comments);
        $total = 0;

        if($allComments != 0){

            foreach($comments as $comment){

                $total += $comment->getRate();

            }

            $moyenne = ($total / $allComments);

        }else{

            $moyenne = 0;

        }



        return $moyenne;

    }

}