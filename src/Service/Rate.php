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


    //TODO Service calcul moyenne des notes
    public function rateAverage($address)
    {

        $repo = $this->em->getRepository(Comment::class);
        $comments = $repo->findByAddress($address);


        return $comments;

    }

}