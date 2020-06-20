<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Department;
use App\Entity\APIToken;
use App\Entity\APIResponse;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/departments", name="get_all_departments")
     */
    public function index()
    {
        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();
        $ready_departments = [];
        foreach ($departments as $value) {
        	$ready_departments[$value->getName()] = $value->getId();
        }
        $response = new APIResponse($ready_departments);
        return $response()[0];
    }
}
