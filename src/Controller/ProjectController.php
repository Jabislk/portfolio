<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProjectController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/project', name: 'app_project')]
    public function index(Request $request,SluggerInterface $slugger): Response
    {
        $projet = new Project();

        $add = $this->createForm(ProjectType::class, $projet);
        $add->handleRequest($request);

        if($add->isSubmitted() && $add->isValid()){
            $imgProjet = $add->get("img")->getData();
            if($imgProjet){
               $originalFileName = pathinfo($imgProjet->getClientOriginalName(),PATHINFO_FILENAME);

               $safeFileName = $slugger->slug($originalFileName);
               $newfileName = $safeFileName.'-'.uniqid().'.'. $imgProjet->guessExtension();

               try{
                $imgProjet->move($this->getParameter('img'), $newfileName);

               }catch(FileException $erreur){

               }
               $projet->setImg($newfileName);
           
            }
            $this->manager->persist($projet);
            $this->manager->flush();

            return $this->redirectToRoute('app_all_project');
        }

        return $this->render('project/index.html.twig', [
            'addForm' => $add->createView(),
        ]);
    }
    
    #[Route('/all_project', name: 'app_all_project')]
    public function all(): Response{
    $projet = $this->manager->getRepository(Project::class)->findAll();
    
    return $this->render('project/home_all_project.html.twig', [
            'projets'=> $projet,
        ]);
    }

    // DELETE
        
    #[Route('/app/admin/delete/project/{id}', name: 'app_delete_project')]
    public function admindelete(Project $projet): Response{
        $this->manager->remove($projet);
        $this->manager->flush();
    
        return $this->redirectToRoute('app_all_project');
    }

        // SINGLE PROJET
    #[Route('/solo/project/{id}', name: 'app_solo_project')]
    public function solo(Project $id): Response{
        $projet = $this->manager->getRepository(Project::class)->find($id);

        return $this->render('project/soloProject.html.twig', [

            'projet' => $projet,

        ]);
    }

    // ---------- EDIT ----------
    /**
     * @Route("admin/edit/projet/{id}", name="app_project_edit")
     */
    public function projetEdit(Project $project, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $imageProjet = $form->get('img')->getData();

            if($imageProjet){
                $originalFilename = pathinfo($imageProjet->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '-' . $imageProjet->guessExtension();

                try{
                    $imageProjet->move(
                        $this->getParameter('img'),
                        $newFilename
                    );
                }catch(FileException $e){
                    
                }
                $project->setImg($newFilename);
            }else{
                dd('Aucune image');
            };

            $this->manager->persist($project);
            $this->manager->flush();
            return $this->redirectToRoute('app_all_project');
        };

        return $this->render("project/editProject.html.twig", [
            'formEditProjet' => $form->createView(),
        ]);
    }
        
}
