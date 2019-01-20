<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if($this->getUser() instanceof UserInterface) {

            return $this->redirectToRoute('app_forgotten_password');

        }

        else{

            $form = $this->createForm(RegistrationFormType::class);
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', ['last_username' => $lastUsername,
                'error' => $error,
                'registrationForm' => $form->createView()
            ]);

        }

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
                return $this->redirectToRoute('message');
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
            return $this->redirectToRoute('message');
        }
        return $this->render('security/forgotten_password.html.twig');
    }
    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        $user = $repository->findByResetToken($token);

        if(empty($user)){
            return $this->redirectToRoute('home');
        }

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

    /**
     * @Route("/changePassword", name="changePassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $user = $this->getUser();

            $checkPass = $encoder->isPasswordValid($user, $form->get('oldPassword'));

            if($checkPass === true){

                $user->setPassword(
                    $encoder->encodePassword(
                        $user, $form->get('plainPassword')->getData())
                );

                $user->eraseCredentials();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

            }else{

                $this->addFlash('danger', 'Votre ancien mot de passe n\'est pas correct');

            }

        }

        return $this->render('security/changePassword.html.twig', [
            'changePasswordForm' => $form->createView()
        ]);

    }

}