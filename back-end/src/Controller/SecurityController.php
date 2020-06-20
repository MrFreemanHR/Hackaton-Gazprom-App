<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use App\Entity\User;
use App\Security\UserAuthenticator;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/api/login", name="api_login")
     * 
     */
    public function api_login(UserPasswordEncoderInterface $passwordEncoder, Request $request, UserAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $response = new Response();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        if ((!$request->query->has('email')) or (!$request->query->has('password')))
        {
            $response->setContent("empty fields");
            $response->setStatusCode(400);
            return $response;       
        }
        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $request->query->get('email')]);
        if (!$user)
        {
            $response->setContent("invalid login");
            $response->setStatusCode(403);
            return $response; 
        }
        if ($passwordEncoder->isPasswordValid($user[0], $request->query->get('password')))
        {
            $guardHandler->authenticateUserAndHandleSuccess(
                $user[0],          // the User object you just created
                $request,
                $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // the name of your firewall in security.yaml
            );
        }
        else
        {
            $response->setContent("invalid password");
            $response->setStatusCode(403);
            return $response; 
        }
        $response->setContent("authenticated");
        $response->setStatusCode(200);
        return $response; 
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
