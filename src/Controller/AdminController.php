<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/manageAddress", name="manageAddress")
     */
    public function manageAddress(){
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $pendingAddress = $repository->pendingAddress();


        return $this->render('admin/manageAddress.html.twig', [
            'pendingAddress' => $pendingAddress,
        ]);
    }
}
