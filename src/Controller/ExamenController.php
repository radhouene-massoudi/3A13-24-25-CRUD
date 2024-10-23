<?php

namespace App\Controller;

use App\Entity\Dep;
use App\Entity\Pc;
use App\Form\DepType;
use App\Form\PcType;
use App\Form\PcUpdateType;
use App\Repository\DepRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/esprit')]
class ExamenController extends AbstractController
{
    #[Route('/examen', name: 'app_examen')]
    public function index(): Response
    {
        return $this->render('examen/index.html.twig', [
            'controller_name' => 'ExamenController',
        ]);
    }

    #[Route('/adddep', name: 'adddep')]
    public function adddep(ManagerRegistry $mr,Request $req)
    {
$dep=new Dep();
$f=$this->createForm(DepType::class,$dep);
$f->handleRequest($req);
if($f->isSubmitted() ){
    $em=$mr->getManager();
    $dep->setNbLaptop(0);
    $em->persist($dep);
    $em->flush();
}
return $this->render('examen/adddep.html.twig',[
   'form'=>$f 
]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetch(DepRepository $repo){

        return $this->render('examen/listdep.html.twig',[
            'deps'=>$repo->findAll()
        ]);
    }
        
    
    #[Route('/addpc/{idofdep}', name: 'addpc')]
    public function addpc(ManagerRegistry $mr,Request $req,$idofdep){
$dep=$mr->getRepository(Dep::class)->find($idofdep);
        $pc=new Pc();
        $form=$this->createForm(PcType::class,$pc);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$mr->getManager();
            $pc->setLaps($dep);
            $dep->setNbLaptop($dep->getNbLaptop()+1);
            $em->persist($pc);
            $em->flush();
        }
        return $this->render('examen/addpc.html.twig',[
'f'=>$form
        ]);
    }

    #[Route('/update/{idofpc}', name: 'addpc')]
    public function updatePc(ManagerRegistry $mr,Request $req,$idofpc){
$pc=$mr->getRepository(Pc::class)->find($idofpc);
        
        $form=$this->createForm(PcUpdateType::class,$pc);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$mr->getManager();
            $em->persist($pc);
            $em->flush();
        }
        return $this->render('examen/addpc.html.twig',[
'f'=>$form
        ]);
    }
}
