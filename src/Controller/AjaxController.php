<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\CommentType;
use App\Form\UpdateAddressType;
use App\Form\UpdateUserType;
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

        //----- AFFICHAGE DES COMMENTAIRES -----//

        $repository = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repository->findAllCommentsByAddress($address);

        return $this->render('ajax/singleView.html.twig', [
            'address' => $address, 'comments' => $comments]);

    }

    /**
     * @Route("/ajax/addComment/{id}", name="addComment", requirements={"id"="[0-9]+"})
     */
    public function addComment(Request $request, Address $address){

        if(!empty($request->request->all())){

            $content = $request->request->get('content');

            $note = $request->request->get('note');

            $comment = new Comment();

            $comment->setDateenvoi(New \DateTime(date('Y-m-d H:i:s')));

            $comment->setUser($this->getUser());

            $comment->setAddress($address);

            $comment->setContent($content);

            $comment->setRate($note);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comment);

            $entityManager->flush();

            $repository = $this->getDoctrine()->getRepository(Comment::class);

            $comments = $repository->findAllCommentsByAddress($address);

            $this->addFlash('success', 'Commentaire posté');

            return $this->render('ajax/comments.html.twig', [
                'comments' => $comments]);

        }else{

            return $this->redirectToRoute('home');

        }

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
    // ROUTE POUR LA GENERATION DU FORMULAIRE DE VALIDATION DES ADDRESSES EN ATTENTE
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

    /**
     * @Route("/ajax/addressResult", name="addressResult")
     */
    // ROUTE POUR LA GENERATION DU FORMULAIRE DE MODIFICATION DES ADDRESSES
    public function addressResult(Request $request, FileUploader $fileuploader)
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

        return $this->render('ajax/addressResult.html.twig', [
            'address' => $address, 'form' => $form->createView(),
        ]);
    }

    // ROUTE POUR L AFFICHAGE DES UTILISATEURS A MODIFIER

    /**
     * @Route("/ajax/userResult", name="userResult")
     */
    // ROUTE POUR LA MODIFICATION DES UTILISATEURS
    public function usersResult(Request $request, FileUploader $fileuploader)
    {
        $user_id = $request->request->get('user_id', null);
        if(empty($user_id) || !preg_match("#^\d+$#", $user_id)){
            $this->addFlash('notice', 'paramètre(s) invalide(s) (user)');
            return $this->redirectToRoute('manageUsers');
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user_id);


        $form = $this->createForm(UpdateUserType::class, $user);

        return $this->render('ajax/userResults.html.twig', [
            'user' => $user, 'form' => $form->createView(),
        ]);
    }
}
