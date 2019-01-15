<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
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

    public function validateAddress(Request $request, FileUploader $fileuploader)
    {
//        $form = $this->createForm(AddressType::class);
//
//        return $this->redirectToRoute('viewAll');

    }
}
