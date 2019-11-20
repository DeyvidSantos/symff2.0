<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $form = $this->createForm(Project::class, $project);
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


        return $this->render('project/new.html.twig');
    }
}