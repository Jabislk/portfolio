<?php

namespace App\Controller;

use App\Entity\About;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {

        $about = new About();
        $about->setTitle('VIJAYARAJAH Jabinesh')
            ->setDescription('lorem ipsum')
            ->setImage('75');

        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
            "about" => $about
        ]);
    }
}
