<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddressType; 
use Symfony\Component\HttpFoundation\File\File;
use App\Service\FileUploader;

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
     * @Route("/carte-reduction", name="viewAll")
     */
    public function viewAll(){
        $repository = $this->getDoctrine()->getRepository(Address::class);
        $address = $repository->findAllValidAddress();


        $locations_lat = [];
        $locations_long = [];
        $address_ids = [];
        $address_cat = [];

        foreach ($address as $adr){

            $loc = $adr->getCoordinates();
            $long = strstr($loc, ',');
            $long = substr($long,1);
            $lat = strstr($loc,',', true);
            $locations_lat[] = $lat;
            $locations_long[] = $long;
            $address_ids[] = $adr->getId();
            $address_cat[] = $adr->getSubCategory()->getCategory()->getLabel();
            $nbAdress = count($locations_lat);
        }

        return $this->render('address/index.html.twig', [
            'address' => $address, 'locations_lat' => $locations_lat, 'locations_long' => $locations_long, 'nbAddress' =>$nbAdress, 'address_ids' => $address_ids, 'address_cat' => $address_cat,
        ]);
    }

     /**
    *@Route("ajout-bon-plan", name="addAddress")
    */
    public function addAdress(Request $request, FileUploader $fileuploader){

        $form = $this->createForm(AddressType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $address = $form->getData();

            if($address->getImage()){
                $filename = $fileuploader->upload($address->getImage(),
                    $this->getParameter('shop_image_directory'));
                $address->setImage($filename);
            }else{
                $address->setImage('avatar.jpg');
            }

            $address->setStatus('pending');

            $entityManager = $this->getDoctrine()->getManager(); 

            $entityManager->persist($address);

            $entityManager->flush();

            $this->addFlash('success', 'Adresse ajoutée, en attente de validation');

            return $this->redirectToRoute('addAddress');

        }

        return $this->render('address/add.html.twig', ['form' => $form->createView()]);

    }
}
