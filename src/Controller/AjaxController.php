<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax/singleView", name="singleView")
     */
    public function singleView(Request $request)
    {
        $address_id = $request->request->get('address_id', null);

        if(empty($address_id) || !preg_match("#^\d+$#", $address_id)){
            return new Response('Parametre(s) invalide(s)');
        }

        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->find($address_id);

        return $this->render('ajax/singleView.html.twig', [
            'address' => $address,
        ]);
    }
}