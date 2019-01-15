<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\UpdateAddressType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $this->addFlash('notice', 'paramètre(s) invalide(s)');
            return $this->redirectToRoute('manageAddress');
        }

        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->find($address_id);


        $form = $this->createForm(UpdateAddressType::class, $address);

        return $this->render('ajax/pendingResult.html.twig', [
            'address' => $address, 'form' => $form->createView(),
        ]);
    }

}
