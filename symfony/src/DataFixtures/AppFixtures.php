<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\EntityManagerInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(EntityManagerInterface $entityManager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $subscription = new Subscription();
            $subscription->setStartDate(new \DateTime('-1 year'));
            $subscription->setEndDate(new \DateTime('+1 year'));

            for ($j = 0; $j < 2; $j++) {
                $product = new Product();
                $product->setLabel($faker->word);
                $subscription->addProduct($product);
            }

            for ($k = 0; $k < 3; $k++) {
                $contact = new Contact();
                $contact->setFirstName($faker->firstName);
                $contact->setName($faker->lastName);
                $contact->setSubscription($subscription);
                $subscription->addContact($contact);
            }

            $entityManager->persist($subscription);
        }

        $entityManager->flush();
    }
}
