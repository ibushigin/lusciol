<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address/single", name="single")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $address = $repository->findAll();


        $locations = [];
        foreach ($address as $adr){

            $location = [];
            $loc = $adr->getCoordinates();
            $long = strstr($loc, ',');
            $long = substr($long,1);
            $lat = strstr($loc,',', true);
            $location[] = $lat;
            $location[] = $long;
            $locations[] = $location;
    }

        return $this->render('address/single.html.twig', [
            'address' => $address, 'locations' => $locations, 'lat' => $lat, 'long' => $long
        ]);
    }


}
