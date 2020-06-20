<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use App\Entity\User;
use App\Entity\APIToken;
use App\Entity\APIResponse;
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
        if ((!$request->query->has('email')) or (!$request->query->has('password')))
        {
            $response = new APIResponse([], 1, "Empty fields");
            return $response()[0];       
        }
        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $request->query->get('email')]);
        if (!$user)
        {
            $response = new APIResponse([], 2, "Invalid login");
            return $response()[0]; 
        }
        if ($passwordEncoder->isPasswordValid($user[0], $request->query->get('password')))
        {
            $token = new APIToken;
            $token->setToken(bin2hex(random_bytes(60)));
            $token->setUserid($user[0]->getId());
            date_default_timezone_set("Europe/Moscow");
            $token->setExperies(new \DateTime('+3 hour'));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($token);
            $manager->flush();
            $guardHandler->authenticateUserAndHandleSuccess(
                $user[0],          // the User object you just created
                $request,
                $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // the name of your firewall in security.yaml
            );
        }
        else
        {
            $response = new APIResponse([], 3, "Invalid password");
            return $response()[0]; 
        }
        $response = new APIResponse(['token' => $token->getToken(), 'experies' => $token->getExperies()->format('d.m.Y H:i:s')]);
        return $response()[0];
    }

    /**
     * @Route("/api/logout", name="api_logout")
     *
     */
    public function api_logout(Request $request)
    {
        $user = $this->getUser();
        if (!$user)
            return new Response("", 403);
        if (!$request->query->has('token'))
        {
            $response = new APIResponse([], 1, "Invalid token");
            return $response()[0];
        }
        $token = $this->getDoctrine()->getRepository(APIToken::class)->findBy(['token' => $request->request->get('token')]);
        if (!$token)
        {
            $response = new APIResponse([], 1, "Invalid token");
            return $response()[0];
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($token);
        $manager->flush();
        return $this->redirect($this->generateUrl('app_logout'));
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
