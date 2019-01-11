<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address/single/{id}", name="viewSingle", requirements={"id"="[0-9]+"})
     */
    public function viewSingle(Address $address)
    {

        if(!$address){
            throw $this->createNotFoundException('Aucun magasin trouvé');
        }
            $loc = $address->getCoordinates();
            $long = strstr($loc, ',');
            $long = substr($long,1);
            $lat = strstr($loc,',', true);

        return $this->render('address/single.html.twig', [
            'address' => $address, 'lat' => $lat, 'long' => $long
        ]);
    }


    /**
     * @Route("/address/all", name="viewAll")
     */
    public function viewAll(){
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $address = $repository->findAll();


        $locations_lat = [];
        $locations_long = [];

        foreach ($address as $adr){

            $loc = $adr->getCoordinates();
            $long = strstr($loc, ',');
            $long = substr($long,1);
            $lat = strstr($loc,',', true);
            $locations_lat[] = $lat;
            $locations_long[] = $long;
            $nbAdress = count($locations_lat);
        }

        return $this->render('address/index.html.twig', [
            'address' => $address, 'locations_lat' => $locations_lat, 'locations_long' => $locations_long, 'nbAddress' =>$nbAdress
        ]);
    }


}
