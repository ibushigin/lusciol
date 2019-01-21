<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\UpdateAddressType;
use App\Form\UpdateUserType;
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
        $allAddress = $repository->findAll();


        return $this->render('admin/manageAddress.html.twig', [
            'pendingAddress' => $pendingAddress, 'allAddress' => $allAddress
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

            $status = $address->getStatus();
            if ($status === 'valid'){
                $this->addFlash('success', 'Succès!');
            } else {
                $this->addFlash('success', 'Succès (Addresse à valider)');
            }

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
        $this->addFlash('warning', 'Adresse supprimée');

        return $this->redirectToRoute('manageAddress');
    }

    /**
     * @Route("/admin/manageUsers", name="manageUsers")
     */

    // PANNEAU DE GESTION DES UTILISATEURS
    public function manageUSers(){
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();


        return $this->render('admin/manageUsers.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/deleteUser/{id}", name="deleteUser", requirements={"id"="[0-9]+"})
     */

    // SUPPRIMER UN UTILISATEUR
    public function deleteUser (Request $request, User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        // Je veux supprimer cet article
        $entityManager->remove($user);
        // Pour valider la suppression
        $entityManager->flush();

        // génération d'un message flash
        $this->addFlash('warning', 'Utilisateur supprimé');

        return $this->redirectToRoute('manageUsers');
    }

    /**
     * @Route("/admin/validateUpdateUser/{id}", name="validateUpdateUser", requirements={"id"="[0-9]+"})
     */

    public function validateUpdateUser(Request $request, FileUploader $fileuploader, User $user)
    {



        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();


            //$article->getImage() contient un objet qui représent le fichier image envoyé
            $file = $user->getAvatar();

            $filename = $file ? $fileuploader->upload($file, $this->getParameter('user_avatar_directory')) : '';

            //je remplace l'attribut imgae qui contient toujours le fichier par le nom du fichier
            $user->setAvatar($filename);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié');

            return $this->redirectToRoute('manageUsers');
        }

        return $this->redirectToRoute('home');

    }

}
