<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address", name="address")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $address = $repository->findAll();


        return $this->render('address/index.html.twig', [
            'address' => $address,
        ]);
    }


}
