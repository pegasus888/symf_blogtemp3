<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use App\Entity\Users;
use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Step 1: Create Categories
        $category1 = new Categories();
        $category1->setCategory('Technology')
            ->setSlug('technology')
            ->setDisabled(0);
        $manager->persist($category1);

        $category2 = new Categories();
        $category2->setCategory('Health')
            ->setSlug('health')
            ->setDisabled(0);
        $manager->persist($category2);

        $category3 = new Categories();
        $category3->setCategory('Others')
            ->setSlug('miscellaneous')
            ->setDisabled(0);
        $manager->persist($category3);

        // Step 2: Create Users
        $user1 = new Users();
        $user1->setUsername('Johndoe')
            ->setEmail('johndoe@example.com')
            ->setPassword(password_hash('password123', PASSWORD_BCRYPT))
            ->setDate(new \DateTime()) // Use the current date
            ->setRole('ROLE_USER');
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setUsername('Janedoe')
            ->setEmail('janedoe@example.com')
            ->setPassword(password_hash('password123', PASSWORD_BCRYPT))
            ->setDate(new \DateTime()) // Use the current date
            ->setRole('ROLE_USER');
        $manager->persist($user2);

        // Step 3: Create Posts
        $post1 = new Posts();
        $post1->setTitle('The Future of Technology')
            ->setContent('Technology is evolving at an unprecedented pace. In this post, we will explore the latest trends in tech.')
            ->setImage('/images/post1.jpg') // Sample image URL
            ->setDate(new \DateTime()) // Use the current date
            ->setSlug('the-future-of-technology')
            ->setUser($user1) // Assign the first user
            ->setCategory($category1); // Assign Technology category
        $manager->persist($post1);

        $post2 = new Posts();
        $post2->setTitle('Maintaining Good Health')
            ->setContent('In this post, we will discuss various tips and strategies for maintaining good health and well-being.')
            ->setImage('/images/post2.jpg') // Sample image URL
            ->setDate(new \DateTime()) // Use the current date
            ->setSlug('maintaining-good-health')
            ->setUser($user2) // Assign the second user
            ->setCategory($category2); // Assign Health category
        $manager->persist($post2);

        $post3 = new Posts();
        $post3->setTitle('Whatever Title')
            ->setContent('In this post, we will discuss bla bla bla...')
            ->setImage('/images/post3.jpg') // Sample image URL
            ->setDate(new \DateTime()) // Use the current date
            ->setSlug('bla-bla')
            ->setUser($user2) // Assign the second user
            ->setCategory($category3); // Assign Health category
        $manager->persist($post3);

        // Flush all created entities to the database at the same time
        $manager->flush();
    }
}
