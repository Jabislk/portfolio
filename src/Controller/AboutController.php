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
        $about->setTitle('')
            ->setDescription('My name is Jabinesh. I’m 22 years old. I’m currently student at LePoles as a web developer junior. My hobbies are photography and cycling. I’m passionate about technology. ')
            ->setImage('75');

        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
            "about" => $about
        ]);
    }
}
