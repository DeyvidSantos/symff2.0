<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProjectType;
use App\Entity\User;
use App\Entity\Project;

class ProjectController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @Route("/projects", name="app_projects")
     * @return Response
     */
    public function index() : Response
    {
        /** @var Project $projects */
        $project = $this->getDoctrine()->getManager()->getRepository(Project::class)->findAll();
        return $this->render('project/index.html.twig', [
           'projects' => $project
        ]);
    }
    

    /**
     * new project
     * @Route("/project/new", name="project_new")
     * @param Request $request
     * @return Response
     */

    public function new(Request $request) : Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                /** @var User $user */
                $user = $this->getUser();
                $user->addProject($project);

                $em = $this->getDoctrine()->getManager();
                $em ->persist($user);
                $em -> flush();

                return $this->redirectToRoute("app_projects");
            } catch (\Exception $e){
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('project/new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }


    /**
     * mostra projeto no id
     *
     * @Route("/project/show/{project}", name="project_show")
     * @param Project $id
     * @return Response
     */    
    public function show(Project $project) : Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }



    /**
     *edição dos bagulho
     *
     * @Route ("/project/edit/{project}", name="project_edit")
     * @param Project $project
     * @param Request $request
     * @return void
     */
    public function edit(Project $project ,Request $request)
    {

        $form = $this->createForm(ProjectType::class, $project);
        $form ->handleRequest($request);

        if($form ->isSubmitted() && $form-> isValid){
            try {
$this->getDoctrine()->getManager()->flush();
return $this->redirectToRoute('app_projects');

            }
            catch (\Exception $e){

                $form->addError(new FormError($e->getMessage()));
            }

        }

        return $this->render('index.html.twig');
    }
}