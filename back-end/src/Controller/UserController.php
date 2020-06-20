<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\APIResponse;
use App\Entity\APIToken;

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
    	foreach ($fields as $value) {
    		if ($value == "")
    		{
    			$response = new APIResponse([], 1, "Empty fields");
    			return $response()[0];		// If there is one or more empty fields
    		}
    	}
    	# Checking for existing user with this email
    	$user = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $fields['email']]);
    	if ($user)
    	{
    		$response = new APIResponse([], 2, "Already registred");
    		return $response()[0];		// If this email is already registred
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
    	$response = new APIResponse("Registration completed");
        return $response()[0];
    }

    /**
     * @Route("/test")
     */
    public function test()
    {
    	dd($this->getUser());
    }

    /**
     * @Route("/profile")
     */
    public function getMyProfile(Request $request)
    {
    	# Check token
    	if (!$request->query->has('token'))
    		return new Response("", 403);
    	$token = $this->getDoctrine()->getRepository(APIToken::class)->findBy(['token' => $request->query->get("token")]);
    	if (!$token)
    	{
    		$response = new APIResponse([], 1, "Invalid token");
    		return $response()[0];
    	}
    	$token = $token[0];
    	$now = new \DateTime;
    	if ($token->getExperies() < $now)
    	{
    		$response = new APIResponse([], 2, "Token experies out");
    		$manager = $this->getDoctrine()->getManager();
    		$manager->remove($token);
    		$manager->flush();
    		return $response()[0];
    	}
    	$user = $this->getDoctrine()->getRepository(User::class)->find($token->getUserid());
    	if (!$user)
    	{
    		$response = new APIResponse([], 3, "Empty user");
    		return $response()[0];
    	}
    	# Return profile
    	$result = [
    		'name'		=> $user->getName(),
    		'surname'	=> $user->getSurname(),
    		'patr'		=> $user->getPatr(),
    		'sex'		=> $user->getSex(),
    		'division'	=> $user->getDivision(),
    		'email'		=> $user->getEmail(),
    	];
    	$response = new APIResponse($result);
    	return $response()[0];
    }

    /**
     * @Route("/profile/avatar")
     */
    public function getAvatar(Request $request)
    {
    	# Check token
    	if (!$request->query->has('token'))
    		return new Response("", 403);
    	$token = $this->getDoctrine()->getRepository(APIToken::class)->findBy(['token' => $request->query->get("token")]);
    	if (!$token)
    	{
    		$response = new APIResponse([], 1, "Invalid token");
    		return $response()[0];
    	}
    	$token = $token[0];
    	$now = new \DateTime;
    	if ($token->getExperies() < $now)
    	{
    		$response = new APIResponse([], 2, "Token experies out");
    		$manager = $this->getDoctrine()->getManager();
    		$manager->remove($token);
    		$manager->flush();
    		return $response()[0];
    	}
    	$user = $this->getDoctrine()->getRepository(User::class)->find($token->getUserid());
    	if (!$user)
    	{
    		$response = new APIResponse([], 3, "Empty user");
    		return $response()[0];
    	}
    	# Return avatar
    	$response = new APIResponse(stream_get_contents($user->getAvatar()));
    	return $response()[0];
    }
}
