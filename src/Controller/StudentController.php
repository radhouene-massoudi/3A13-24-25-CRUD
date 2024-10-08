<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentformType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr): Response
    {
        $st=new Student();
        $st->setName('John');
        $st->setAge(20);
        $st->setCreatedAt(new \DateTimeImmutable('now'));
$em=$mr->getManager();
$em->persist($st);
$em->flush();
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/show', name: 'show')]
    public function students(ManagerRegistry $mr)
    {
        $em=$mr->getRepository(Student::class);
        $students=$em->findAll();
        //dd($students);
        return $this->render('student/show.html.twig',[
            'st'=>$students
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(ManagerRegistry $mr,$id)
    {
        $em=$mr->getRepository(Student::class);
        $student=$em->find($id);
        $em=$mr->getManager();
        if($student!=null){
        $em->remove($student);
        $em->flush();
        //dd($students);
        return $this->redirectToRoute('show');
    }else{
        return  new Response("id doesn't existe");
    }
    }
    #[Route('/addstf', name: 'addF')]
    public function addBasedOnForm(ManagerRegistry $mr,Request $req)
    {
        $st=new Student();
        $st->setName('esprit');
$f=$this->createForm(StudentformType::class,$st);
$f->handleRequest($req);
if($f->isSubmitted()){
$em=$mr->getManager();
$em->persist($st);
$em->flush();
return $this->redirectToRoute('show');
}
return $this->render('student/addst.html.twig',[
    'f'=>$f->createView()
]);
    }
}
