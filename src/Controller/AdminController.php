<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\UpdateAddressType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/validateAddress/{id}", name="validateAddress", requirements={"id"="[0-9]+"})
     */

    public function validateAddress(Request $request, FileUploader $fileuploader, Address $address)
    {

        $form = $this->createForm(UpdateAddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $address = $form->getData();


            //$article->getImage() contient un objet qui représent le fichier image envoyé
            $file = $address->getImage();

            $filename = $file ? $fileuploader->upload($file, $this->getParameter('shop_image_directory')) : '';

            //je remplace l'attribut imgae qui contient toujours le fichier par le nom du fichier
            $address->setImage($filename);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            $this->addFlash('success', 'Adresse ajoutée');

            return $this->redirectToRoute('manageAddress');
        }

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/admin/validateAddressDirectly/{id}", name="validateAddressDirectly", requirements={"id"="[0-9]+"})
     */

    public function validateAddressDirectly(Request $request, Address $address)
    {

            $address->setStatus('valid');

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            $this->addFlash('success', 'Adresse ajoutée');

            return $this->redirectToRoute('manageAddress');
    }

    /**
     * @Route("/admin/rejectAddress/{id}", name="rejectAddress", requirements={"id"="[0-9]+"})
     */

    public function rejectAddress (Request $request, Address $address)
    {
        $entityManager = $this->getDoctrine()->getManager();
        // Je veux supprimer cet article
        $entityManager->remove($address);
        // Pour valider la suppression
        $entityManager->flush();

        // génération d'un message flash
        $this->addFlash('warning', 'Article supprimé');

        return $this->redirectToRoute('manageAddress');
    }
}
