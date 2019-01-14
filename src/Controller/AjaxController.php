<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\FileUploader;
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

    /**
     * @Route("/ajax/returnToResult", name="returnToResult")
     */

    public function returnToResult(Request $request)
    {
        $result_content = $request->request->get('result_content', null);

        return $this->render('ajax/returnToResult.html.twig', [
            'result_content' => $result_content,
        ]);
    }

    /**
     * @Route("/ajax/pendingResult", name="pendingResult")
     */

    public function pendingResult(Request $request, FileUploader $fileuploader)
    {
        $address_id = $request->request->get('address_id', null);
        if(empty($address_id) || !preg_match("#^\d+$#", $address_id)){
            return new Response('Parametre(s) invalide(s)');
        }

        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->find($address_id);

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $address = $form->getData();

            //$article->getImage() contient un objet qui représent le fichier image envoyé
            $file = $address->getImage();

            $filename = $file ? $fileuploader->upload($file, $this->getParameter('shop_image_directory')) : '';

            //je remplace l'attribut imgae qui contient toujours le fichier par le nom du fichier
            $address->setImage($filename);

            $address->setStatus('pending');

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($address);

            $entityManager->flush();

            $this->addFlash('success', 'Adresse ajoutée');

            return $this->redirectToRoute('viewAll');

        }

        return $this->render('ajax/pendingResult.html.twig', [
            'address' => $address, 'form' => $form->createView(),
        ]);
    }

}
