<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SkillController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @Route("/skill", name="app_skill")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $imgSkill = $form->get('img')->getData();

            if($imgSkill){
                $originalFilename = pathinfo($imgSkill->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'-'.$imgSkill->guessExtension();

                try{
                    $imgSkill->move(
                        $this->getParameter('img'),
                        $newFilename
                    );
                }catch(FileException $e){

                }

                $skill->setImg($newFilename);
            }else{
                dd("Aucune image");
            }
            $this->manager->persist($skill);
            $this->manager->flush();
            
            return $this->redirectToRoute('app_home');
        }

        return $this->render('skill/index.html.twig', [
            'skillForm' => $form->createView(),
        ]);
    }

        /**
     * @Route("/all/skill", name="app_all_skill")
     */
    public function allSkill(): Response
    {

        $skill = $this->manager->getRepository(Skill::class)->findAll();

        return $this->render('skill/allSkills.html.twig', [
            'skill' => $skill,
        ]);
    }

    // DELETE
    #[Route('/app/admin/delete/skill/{id}', name: 'app_delete_skill')]
    public function admindelete(Skill $skill): Response{
        $this->manager->remove($skill);
        $this->manager->flush();
    
        return $this->redirectToRoute('app_all_skill');
    }

    // EDIT

    // ---------- EDIT ----------

    /**
     * @Route("admin/edit/skill/{id}", name="app_skill_edit")
     */
    public function skillEdit(Skill $skill, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $imgSkill = $form->get('img')->getData();

            if($imgSkill){
                $originalFilename = pathinfo($imgSkill->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '-' . $imgSkill->guessExtension();

                try{
                    $imgSkill->move(
                        $this->getParameter('img'),
                        $newFilename
                    );
                }catch(FileException $e){
                    
                }
                $skill->setImg($newFilename);
            }else{
                dd('Aucune photo disponible');
            };

            $this->manager->persist($skill);
            $this->manager->flush();
            return $this->redirectToRoute('app_all_skill');
        };

        return $this->render("skill/editSkill.html.twig", [
            'formEditSkill' => $form->createView(),
        ]);
    }
}