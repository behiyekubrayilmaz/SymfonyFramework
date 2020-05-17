<?php

namespace App\Controller\Admin;


use App\Repository\HouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_admin")
     */
    public function index()
    {
        return $this->render('admin/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    

}
