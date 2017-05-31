<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       //die('todos');
     //  return $this->render('default/index.html.twig', array(
       //     'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //));
         //replace this example code with whatever you need
        return $this->render('todo/index.html.twig');
    }
    /**
     * @Route("/todos", name="todo_list")
     */ 
        public function listAction()
     {
      // die('liste');
            $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
            
            return $this->render('todo/index.html.twig', array(
               'todos' => $todos 
            // echo 'hello'; 
          
            ));
            
    }
    
     /**
     * @Route("/todo/create", name="todo_create")
     */ 
        public function createAction(Request $request)
     {
      // die('liste');
            $todo = new Todo;
            $form =$this->createFormBuilder($todo)
                   ->add('name', TextType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                   ->add('categoriy', TextType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                    ->add('description', TextareaType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                    ->add('priority', ChoiceType::class,array('choices'=> array('Low'=>'Low', 'Normal'=>'Normal', 'High'=>'High'),'attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                   ->add('dueDate', DateTimeType::class,array('attr'=> array('class'=>'formcontrol', 'style'=>'margin-bottom:15px')))
                   ->add('save', SubmitType::class,array('attr'=> array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))


                    ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
              //  die('yoyo');
                $name = $form['name']->getData();
            $categoriy = $form['categoriy']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['dueDate']->getData();
            
            $now = new\DateTime('now');
            
            $todo->setName($name);
             $todo->setCategoriy($categoriy);
              $todo->setDescription($description);
               $todo->setPriority($priority);
                  $todo->setDueDate($dueDate);
                     $todo->setCreateDate($now);
                     
         $em= $this->getDoctrine()->getManager();
         $em->persist($todo);
         $em->flush();
         $this->addFlash(
                 'notice',
                 'Todo Added'
                 );
         
              return $this->redirectToRoute('todo_list');
              
               
            
            
            
            
        }
            
                    return $this->render('todo/create.html.twig',array(
                        'form' => $form->createView()
                    ));
            
    }
    
    /**
     * @Route("/edit/{id}", name="todo_edit")
     */ 
        public function editAction($id,Request $request)
     {
       $todo = $this->getDoctrine()
                    ->getRepository('AppBundle:Todo')
                    ->find($id);
         $now = new\DateTime('now');
      
       
       $todo->setName($todo->getName());
             $todo->setCategoriy($todo->getCategoriy());
              $todo->setDescription($todo->getDescription());
               $todo->setPriority($todo->getPriority());
                  $todo->setDueDate($todo->getDueDate());
                     $todo->setCreateDate($now);
       
        
            $form =$this->createFormBuilder($todo)
                   ->add('name', TextType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                   ->add('categoriy', TextType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                    ->add('description', TextareaType::class,array('attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                    ->add('priority', ChoiceType::class,array('choices'=> array('Low'=>'Low', 'Normal'=>'Normal', 'High'=>'High'),'attr'=> array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
                   ->add('dueDate', DateTimeType::class,array('attr'=> array('class'=>'formcontrol', 'style'=>'margin-bottom:15px')))
                   ->add('save', SubmitType::class,array('attr'=> array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
                  ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
              //  die('yoyo');
                $name = $form['name']->getData();
            $categoriy = $form['categoriy']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['dueDate']->getData();
            
            $now = new\DateTime('now');
             $em= $this->getDoctrine()->getManager();
             $todo = $em->getRepository('AppBundle:Todo')->find($id);
            
            $todo->setName($name);
             $todo->setCategoriy($categoriy);
              $todo->setDescription($description);
               $todo->setPriority($priority);
                  $todo->setDueDate($dueDate);
                     $todo->setCreateDate($now);
                     
        
         $em->flush();
         $this->addFlash(
                 'notice',
                 'Todo Updated'
                 );
         
              return $this->redirectToRoute('todo_list');
       
             }
            return $this->render('todo/edit.html.twig',  array(
                'todo' => $todo,
                'form' => $form->createView()
            ));
            
   
    
  
    

     }
      /**
     * @Route("/details/{id}", name="todo_details")
     */ 
        public function detailsAction($id)
     {
      // die('liste');
            $todo = $this->getDoctrine()
                    ->getRepository('AppBundle:Todo')
                    ->find($id);
            
            return $this->render('todo/details.html.twig',  array(
                'todo' => $todo
            ));
            
     }
      /**
     * @Route("/delete/{id}", name="todo_delete")
     */ 
        public function deleteAction($id)
     {
           // die('coco');
             $em = $this->getDoctrine()->getManager();
      $todo = $em->getRepository('AppBundle:Todo')->find($id);
      
      $em->remove($todo);
      $em->flush();
       $this->addFlash(
                 'error',
                 'Todo Removed'
                 );
         
              return $this->redirectToRoute('todo_list');
      
      
            
     }
   
   
}
            
