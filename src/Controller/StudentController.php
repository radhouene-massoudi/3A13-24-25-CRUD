<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\AddStsType;
use App\Form\StudentformType;
use App\Repository\KlassRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/klass', name: 'klass')]
    public function fetchKlass(KlassRepository $repo){
        return $this->render('klass/showKlass.html.twig',[
            'k'=>$repo->findAll()
        ]);
    }
    
    #[Route('/addst/{idofclass}', name: 'addst')]
    public function addst(KlassRepository $repo,$idofclass,Request $req, ManagerRegistry $mr){
$classroom=$repo->find($idofclass);
$st=new Student();
$form=$this->createForm(AddStsType::class,$st);
$form->handleRequest($req);
if($classroom!=null){
if($form->isSubmitted()){
    $st->setCreatedAt(new \DateTimeImmutable('now'));
$st->setKlass($classroom);
    $em=$mr->getManager();

$em->persist($st);
$em->flush();
return $this->redirectToRoute('show');
}}else{
    return new Response("id doesn't existe");
}
        return $this->render('klass/addst.html.twig',[
            'f'=>$form
        ]);
    }

    #[Route('/myfindall', name: 'myfindall')]
    public function myfindall(EntityManagerInterface $em,StudentRepository $repo){
       // $k="3A13";
//$dql=$em->createQuery("select s from App\Entity\Student s join s.klass c where c.name=:n");
//$dql->setParameter('n',$k);
//$result=$dql->getResult();
//dd($result[0][1]);
//$result=$repo->findAllByDql('3A12');
$result=$repo->findAllByQb();
dd($result);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $req,StudentRepository $repo)
    {
        if($req->isMethod('POST')){

            $data=$req->get('nameofstudent');
            $result=$repo->findAllByName($data);
            dd($result);
            return $this->render('student/show.html.twig',[
                'st'=>$result
            ]);
        }else{
            return new Response('good');
        }

        
    }
}
