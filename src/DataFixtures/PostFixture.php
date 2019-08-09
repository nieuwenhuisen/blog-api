<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PostFixture extends Fixture implements DependentFixtureInterface
{
    public const AMOUNT = 50;

    /** @var Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('nl_NL');
    }

    public function load(ObjectManager $manager): void
    {
        $categoryKeys= array_keys(CategoryFixture::CATEGORIES);

        for ($i = 0; $i < self::AMOUNT; $i++) {
            $post = new Post();

            $post->setTitle($this->faker->unique()->sentence);
            $post->setSlug($this->faker->unique()->slug);
            $post->setContent($this->faker->text);

            if ($this->faker->boolean) {
                $post->setPublicationDate($this->faker->dateTimeBetween('-2 years', 'now'));
            }

            shuffle($categoryKeys);
            $randomCategoryKeys = array_slice($categoryKeys, 0, $this->faker->numberBetween(1, 4));

            foreach ($randomCategoryKeys as $randomCategoryKey) {
                /** @var Category $category */
                $category = $this->getReference('category_'.$randomCategoryKey);
                $post->addCategory($category);
            }

            $manager->persist($post);
            $this->addReference('post_'.$i, $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixture::class];
    }
}
