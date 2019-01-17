<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,
                             FileUploader $fileuploader, AuthenticationUtils $authenticationUtils): Response
    {

        $form = $this->createForm(RegistrationFormType::class);

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var User */

            $user = $form->getData();
            dump($user);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //suppression du mdp en clair
            $user->eraseCredentials();
            //attribution du role par faut à l'utilisateur qui s'inscrit
            $user->setRoles(['ROLE_USER']);

            //traitement du formulaire d'upload d'image
            //on m'a envoyé une image
            if($user->getAvatar()){
            $filename = $fileuploader->upload($user->getAvatar(),
                $this->getParameter('user_avatar_directory'));
            //j'injecte le nom du fichier dans la propriété image
            $user->setAvatar($filename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername,
            'error' => $error,
            'registrationForm' => $form->createView()
        ]);
    }
}