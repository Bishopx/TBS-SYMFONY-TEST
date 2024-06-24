<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Subscription|null find($id, $lockMode = null, $loadData = true)
 * @method Subscription|null findOneBy($criteria, $orderBy = null, $limit = null, $offset = null)
 * @method Subscription[]    findAll($orderBy = null, $limit = null, $offset = null)
 * @method Subscription[]    findBy($criteria, $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function findSubscriptionByContactId(int $contactId): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->join('s.contact', 'c')
            ->where('c.id = :contactId')
            ->setParameter('contactId', $contactId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createSubscription(Contact $contact, Product $product, \DateTimeInterface $beginDate, \DateTimeInterface $endDate): Subscription
    {
        $subscription = new Subscription();
        $subscription->setContact($contact);
        $subscription->setProduct($product);
        $subscription->setBeginDate($beginDate);
        $subscription->setEndDate($endDate);
        $subscription->setCreatedAt(new \DateTime());

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        return $subscription;
    }

    public function updateSubscription(int $subscriptionId, Contact $contact, Product $product, \DateTimeInterface $beginDate, \DateTimeInterface $endDate): ?Subscription
    {
        $subscription = $this->find($subscriptionId);

        if (!$subscription) {
            return null;
        }

        $subscription->setContact($contact);
        $subscription->setProduct($product);
        $subscription->setBeginDate($beginDate);
        $subscription->setEndDate($endDate);

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        return $subscription;
    }

    public function deleteSubscription(int $subscriptionId): bool
    {
        $subscription = $this->find($subscriptionId);

        if (!$subscription) {
            return false;
        }

        $this->entityManager->remove($subscription);
        $this->entityManager->flush();

        return true;
    }
}