<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Division;
use App\Entity\APIResponse;

class DivisionController extends AbstractController
{
    /**
     * @Route("/divisions", name="get_all_divisions")
     */
    public function index()
    {
        $divisions = $this->getDoctrine()->getRepository(Division::class)->findAll();
        $ready_divisions = [];
        foreach ($divisions as $value) {
        	$ready_divisions[$value->getName()] = $value->getId();
        }
        $response = new APIResponse(json_encode($ready_divisions, JSON_UNESCAPED_UNICODE));
        return $response()[0]; 
    }
}
