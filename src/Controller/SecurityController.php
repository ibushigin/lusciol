<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request,
                          UserPasswordEncoderInterface $passwordEncoder,
                          GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,
                          FileUploader $fileuploader): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // ------------------------------------------------------- //


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername,
            'error' => $error,
            ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register( Request $request,
                              UserPasswordEncoderInterface $passwordEncoder,
                              GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,
                              FileUploader $fileuploader): Response{

        $form = $this->createForm(RegistrationFormType::class);
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
            $filename = $fileuploader->upload($user->getAvatar(),
                $this->getParameter('user_avatar_directory'));
            //j'injecte le nom du fichier dans la propriété image
            $user->setAvatar($filename);



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

        return $this->render('security/register.html.twig',
            ['registrationForm' => $form->createView()]);

    }


    /**
     * @Route("/user/infos", name="userInfo")
     */
    public function showActiveUser(Request $request){

        //restriction d'accès aux utilisatilisateurs connectés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        dump($user);

        return $this->render('security/user.html.twig', ['moi' => $user]);
    }

    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Oubli mot de passe'))
                ->setFrom('z8rpz42@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Voici le token pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('notice', 'Mail envoyé');
            return $this->redirectToRoute('home');
        }
        return $this->render('security/forgotten_password.html.twig');
    }
    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Token inconnu');
                return $this->redirectToRoute('home');
            }
            $user->setResetToken(null);
            if($request->request->get('password') === $request->request->get('repeatedPassword')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $entityManager->flush();
                $this->addFlash('notice', 'Mot de passe mis à jour');
                return $this->redirectToRoute('home');
            }else{
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas');
            }
        }else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }


}
