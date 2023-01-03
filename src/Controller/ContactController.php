<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request): Response
    {
        $contact = new Contact;

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($contact);
            $this->manager->flush();

            return $this->redirectToRoute('app_contact');
        }
        
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

    #[Route('/all/message', name: 'app_all_message')]
    public function message(): Response{
        $message = $this->manager->getRepository(Contact::class)->findAll();

        return $this->render('contact/messagerie.html.twig', ['message' => $message,]);
    }

    /**
     * @Route("/admin/message/delete/{id}", name="app_delete_message")
     */
    public function delete(Contact $message): Response{
        $this->manager->remove($message);
        $this->manager->flush();

        return $this->redirectToRoute('app_all_message');
    }
}
