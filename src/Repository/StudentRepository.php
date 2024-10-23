<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    //    /**
    //     * @return Student[] Returns an array of Student objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllByDql($class)
        {
            $query = $this
            ->getEntityManager()
            ->createQuery('select s from App\Entity\Student s join s.klass c where c.name=:n');
        $query->setParameter('n',$class);
        return $query->getResult();
        }

        public function findAllByQb()
        {
            return  
            $this
            ->createQueryBuilder('a')
            ->select('a.name ')
            ->orderBy('a.name','DESC')
            //->join('a.klass','c')
            //->addSelect('c.name')
            //->where("c.name = '3A13'")
            ->getQuery()
            ->getDQL();

        }
        public function findAllByName($name)
        {
            return  
            $this
            ->createQueryBuilder('a')
            ->select('a.name ')
            ->orderBy('a.name','DESC')
            ->where('a.name=:n')
            ->setParameter('n',$name)
            ->join('a.klass','c')
            //->addSelect('c.name')
            //->where("c.name = '3A13'")
            ->getQuery()
            ->getSQL();

        }
}
