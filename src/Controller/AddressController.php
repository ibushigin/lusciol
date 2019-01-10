<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address", name="address")
     */
    public function index()
    {
        $respository = $this->getDoctrine()->getRepository(AddressController::class);
        $address = $respository->findAll();

        return $this->render('address/index.html.twig', [
            'address' => $address,
        ]);
    }


}
