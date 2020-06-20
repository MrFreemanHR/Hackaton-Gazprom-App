<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

class UserController extends AbstractController
{

	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registrate", name="registrate_user")
     */
    public function registarteUser(Request $request)
    {
    	# Getting user fields from Request
    	$fields = [];
    	$fields['name'] = $request->query->get('name');
    	$fields['surname'] = $request->query->get('surname');
    	$fields['patr'] = $request->query->get('patr');
    	$fields['sex'] = $request->query->get('sex');
    	$fields['division'] = $request->query->get('division');
    	$fields['email'] = $request->query->get('email');
    	$fields['password'] = $request->query->get('password');
    	$fields['passagain'] = $request->query->get('passagain');
    	$response = new Response();
    	$response->headers->set("Access-Control-Allow-Origin", "*");
    	foreach ($fields as $value) {
    		if ($value == "")
    		{
    			$response->setContent("empty field");
    			$response->setStatusCode(400);
    			return $response;		// If there is one or more empty fields
    		}
    	}
    	# Checking for existing user with this email
    	$user = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $fields['email']]);
    	if ($user)
    	{
    		$response->setContent("already registred");
			$response->setStatusCode(409);
    		return $response;		// If this email is already registred
    	}
    	# Create User entity object
    	$user = new User;
    	$user->setName($fields['name']);
    	$user->setSurname($fields['surname']);
    	$user->setPatr($fields['patr']);
    	$user->setSex($fields['sex']);
    	$user->setRating(0);
    	$user->setPhone("-");
    	$user->setDepartment($fields['department']);
    	$user->setEmail($fields['email']);
    	$user->setPassword($this->passwordEncoder->encodePassword($user, $fields['password']));
    	$file = base64_encode(file_get_contents("img/no_img.png"));
    	$user->setAvatar($file);
    	# Persist for Database
    	$manager = $this->getDoctrine()->getManager();
    	$manager->persist($user);
    	$manager->flush();
    	# Return answer
    	$response->setContent("registred");
		$response->setStatusCode(200);
        return $response;
    }
}
