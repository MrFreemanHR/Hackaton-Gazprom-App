<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Department;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/departments", name="get_all_departments")
     */
    public function index()
    {
        $departments = $this->getDoctrine()->getRepository(Department::class);
    }
}
