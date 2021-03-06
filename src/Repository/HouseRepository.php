<?php

namespace App\Repository;

use App\Entity\House;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method House|null find($id, $lockMode = null, $lockVersion = null)
 * @method House|null findOneBy(array $criteria, array $orderBy = null)
 * @method House[]    findAll()
 * @method House[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, House::class);
    }

    // /**
    //  * @return House[] Returns an array of House objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?House
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAllHouses(): array
    {
        $conn= $this->getEntityManager()->getConnection();
        $sql='
        SELECT h.*,c.title as catname,u.name,u.surname FROM house h
        JOIN category c ON c.id= h.category_id
        JOIN user u ON u.id= h.userid
        ORDER BY h.price DESC 
        ';
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllHouseFirst($status): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
    SELECT h.*,c.title as catname, u.name, u.surname FROM house h
    JOIN category c ON c.id = h.category_id
    JOIN user u ON u.id = h.userid
    WHERE h.status =:status
    ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['status' => $status]);

        return $stmt->fetchAll();
    }





}
