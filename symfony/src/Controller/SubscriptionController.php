<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\Framework\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

class SubscriptionController extends AbstractController
{
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    #[Route('/subscription/{contactId}', name: 'app_subscription_get', methods: ['GET'])]
    public function get(int $contactId): Response
    {
        $subscription = $this->subscriptionRepository->findSubscriptionByContactId($contactId);

        if (!$subscription) {
            throw $this->createNotFoundException('Subscription not found for contact ID: ' . $contactId);
        }

        $responseData = [
            'id' => $subscription->getId(),
            'contactId' => $subscription->getContact()->getId(),
            'productId' => $subscription->getProduct()->getId(),
            'beginDate' => $subscription->getBeginDate()->format('Y-m-d'),
            'endDate' => $subscription->getEndDate()->format('Y-m-d'),
            'createdAt' => $subscription->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $subscription->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    
        return new JsonResponse($responseData, Response::HTTP_OK);
    }


    #[Route('/subscription/new', name: 'app_subscription_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $contactId = $request->request->getInt('contactId');
        $productId = $request->request->get('productId');
        $beginDate = new \DateTime($request->request->get('beginDate'));
        $endDate = new \DateTime($request->request->get('endDate'));
    
        if (!$contactId || !$productId || !$beginDate || !$endDate) {
            throw new \InvalidArgumentException('Missing required parameters.');
        }
    
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);
        if (!$contact) {
            throw new \NotFoundException('Contact not found.');
        }

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!$product) {
            throw new \NotFoundException('Product not found.');
        }
    
        $subscription = $this->subscriptionRepository->createSubscription(
            $contact,
            $product,
            $beginDate,
            $endDate
        );
    
        $responseData = [
            'id' => $subscription->getId(),
            'contactId' => $subscription->getContact()->getId(),
            'productName' => $subscription->getProductName(),
            'beginDate' => $beginDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'createdAt' => $subscription->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    
        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }

    #[Route('/subscriptions/{id}/edit', name: 'app_subscription_edit', methods: ['PUT'])]
    public function edit(Request $request, Subscription $subscription): Response
    {
        $contactId = $request->request->getInt('contactId');
        $productId = $request->request->get('productId');
        $beginDate = new \DateTime($request->request->get('beginDate'));
        $endDate = new \DateTime($request->request->get('endDate'));
    
        if (!$contactId || !$productId || !$beginDate || !$endDate) {
            throw new \InvalidArgumentException('Missing required parameters.');
        }
    
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);
        if (!$contact) {
            throw new \NotFoundException('Contact not found.');
        }

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!$product) {
            throw new \NotFoundException('Product not found.');
        }
    
        $this->subscriptionRepository->updateSubscription(
            $subscription->getId(),
            $contact,
            $product,
            $beginDate,
            $endDate
        );
    
        $responseData = [
            'id' => $subscription->getId(),
            'contactId' => $subscription->getContact()->getId(),
            'productName' => $subscription->getProductName(),
            'beginDate' => $beginDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'updatedAt' => $subscription->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    
        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/subscriptions/{id}', name: 'app_subscription_delete', methods: ['DELETE'])]
    public function delete(Request $request, Subscription $subscription): Response
    {
        if (!$subscription) {
            throw $this->createNotFoundException('Subscription not found.');
        }

        $this->entityManager->remove($subscription);
        $this->entityManager->flush();

        $this->subscriptionRepository->deleteSubscription($subscription->getId());

        $responseData = [
            'message' => 'Subscription deleted successfully.',
            'id' => $subscription->getId(),
        ];
    
        return new JsonResponse($responseData, Response::HTTP_OK);
    }
}