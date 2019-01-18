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

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class);

        $form = $this->createForm(MessageType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $message = $form->getData();

            $message->setSubject();

            $message->setContent();

            $mail = (new \Swift_Mailer($request->request->get('subject')))
                ->setFrom($user->getEmail())
                //TODO créer un mail pour recevoir les messages
                ->setTo('inbehis@gmail.com')
                ->setBody(
                    $request->request->get('content'),
                    "text/html"
                );

            $mailer->send($mail);
            $this->addFlash('notice', 'Message envoyé');
            return $this->redirectToRoute('message');

        }

        return $this->render('message/index.html.twig', [
            'messageForm' => $form->createView()
        ]);
    }
}
