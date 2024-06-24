<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Contact|null find($id, $lockMode = null, $loadData = true)
 * @method Contact|null findOneBy($criteria, $orderBy = null, $limit = null, $offset = null)
 * @method Contact[]    findAll($orderBy = null, $limit = null, $offset = null)
 * @method Contact[]    findBy($criteria, $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    // Custom methods for Contact entity
}