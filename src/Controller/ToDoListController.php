<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);//getting all values in the database and sort it by id
       return $this->render('to_do_list/index.html.twig',['tasks'=>$tasks]);//assign the variables to the view using php array
       
       /*return $this->render('to_do_list/index.html.twig', [
           'controller_name' => 'ToDoListController',
       ]); here is also an array used*/
        
    }
    
    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)//form is sent with post data -> request-> get( name of input)
    {
        $title = trim($request->request->get('title'));
        if(empty($title)){
            return $this->redirectToRoute('to_do_list'); //redirect back
        }
        $entityManager = $this->getDoctrine()->getManager();
        
        $task = new Task;
        $task->setTitle($title);
        $entityManager->persist($task);//preparation
        $entityManager->flush();//save (Multiple persisted $tasks possible)
        
        return $this->redirectToRoute('to_do_list'); //redirect back
    }
    
    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());//!null returns 1
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }
    
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }
}
