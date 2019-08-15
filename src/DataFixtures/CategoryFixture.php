<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const CATEGORIES = [
        'cars' => 'Cars',
        'gaming' => 'Gaming',
        'sports' => 'Sports',
        'nature' => 'Nature',
        'science' => 'Science',
        'lifestyle' => 'Lifestyle',
        'marketing' => 'Marketing',
        'career' => 'Career',
        'technology' => 'Technology',
        'education' => 'Education',
        'social_media' => 'Social Media',
        'finance' => 'Finance',
        'economics' => 'Economics',
        'entertainment' => 'Entertainment',
        'political' => 'Political',
        'medical' => 'Medical',
        'shopping' => 'Shopping',
        'business' => 'Business',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $key => $name) {
            $category = new Category($name);
            $manager->persist($category);

            $this->addReference('category_'.$key, $category);
        }

        $manager->flush();
    }
}
