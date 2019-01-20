<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(MessageType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $message = $form->getData();
            if (($message->getSubject()) === "") {
                $this->addFlash('danger', 'Veuillez choisir un objet de message');
                return $this->redirectToRoute('message');
            } else {
                $mail = (new \Swift_Message($message->getSubject()))
                    ->setFrom($user->getEmail())
                    ->setTo($this->getParameter('mail'))
                    ->setBody(
                        'envoyé par ' . $user->getEmail() . '<br>' . $message->getContent(),
                        "text/html"
                    );

                $mailer->send($mail);
                $this->addFlash('success', 'Message envoyé');

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);

                return $this->redirectToRoute('message');

            }
        }

        return $this->render('message/index.html.twig', [
            'messageForm' => $form->createView()
        ]);
    }
}
