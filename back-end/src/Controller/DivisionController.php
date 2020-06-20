<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Division;

class DivisionController extends AbstractController
{
    /**
     * @Route("/divisions", name="get_all_divisions")
     */
    public function index()
    {
    	$response = new Response();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $divisions = $this->getDoctrine()->getRepository(Division::class)->findAll();
        $ready_divisions = [];
        foreach ($divisions as $value) {
        	$ready_divisions[$value->getName()] = $value->getId();
        }
        $response->setContent(json_encode($ready_divisions, JSON_UNESCAPED_UNICODE));
        $response->setStatusCode(200);
        return $response; 
    }
}
