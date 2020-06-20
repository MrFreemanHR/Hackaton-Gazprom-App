<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Ticket;
use App\Entity\Division;
use App\Entity\APIResponse;
use App\Entity\APIToken;
use App\Entity\User;

class TicketsController extends AbstractController
{
    /**
     * @Route("/tickets/division/{id<\d+>}", name="tickets_by_division")
     */
    public function index($id)
    {
    	$tickets = $this->getDoctrine()->getRepository(Ticket::class)->findBy(['division' => $id]);
    	$tickets = array_map(function($ticket){ return $ticket->getAll(); }, $tickets);
       	$response = new APIResponse($tickets);
       	return $response()[0];
    }

    /**
     * @Route("/tickets/division/{id<\d+>}/create", name="create_ticket_in_division")
     */
    public function createTicketInDivision($id, Request $request)
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
    	# Check user's division
    	if ($user->getDivision() == $id)
    	{
    		# Check input fields
    		$fields = [];
    		$fields['theme'] = $request->query->get('theme');
    		$fields['problem'] = $request->query->get('problem');
    		$fields['answer'] = $request->query->get('answer');
    		$fields['keywords'] = $request->query->get('keywords');
    		foreach ($fields as $value) {
    			if ($value == "")
    			{
    				$response = new APIResponse([], 4, "Empty fields");
    				return $response()[0];
    			}
    		}
    		# Create ticket
    		$ticket = new Ticket;
    		$ticket->setTheme($fields['theme']);
    		$ticket->setProblem($fields['problem']);
    		$ticket->setAnswer($fields['answer']);
    		$ticket->setKeywords($fields['keywords']);
    		$ticket->setCreator($user->getId());
    		$ticket->setState(1);
    		$ticket->setDivision($id);
    		$manager = $this->getDoctrine()->getManager();
    		$manager->persist($ticket);
    		$manager->flush();
    		# Add new ticket to user's open tickets
    		$tickets_user = $user->getTickets();
    		$tickets_user['opened'][] = $ticket->getId();
    		$user->setTickets($tickets_user);
    		$manager->persist($user);
    		$manager->flush();
    		# Add new ticket to division's open tickets
    		$division = $this->getDoctrine()->getRepository(Division::class)->find($id);
    		$tickets_division = $division->getTickets();
    		$tickets_division['opened'][] = $ticket->getId();
    		$division->setTickets($tickets_division);
    		$manager->persist($division);
    		$manager->flush();
    		# Return response
    		$response = new APIResponse("ticket created");
    		return $response()[0];
    	}
    	else
    	{
    		# Not allowed
    		$response = new APIResponse([], 5, "not allowed");
    		return $response()[0];
    	}
    }

}
