<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixtures per generar dades de prova.
 * Crea 5 usuaris i 20 productes assignats aleatòriament als usuaris.
 * Utilitza Faker per generar dades realistes.
 */
class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_ES'); // Faker en castellà per dades més realistes
        $users = [];

        // Crear 5 usuaris de prova
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setName($faker->name());
            $user->setEmail('user' . $i . '@symfopop.com');

            // Totes les contrasenyes de prova són "password123"
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password123')
            );

            $manager->persist($user);
            $users[] = $user;
        }

        // Crear 20 productes assignats aleatòriament als usuaris
        $categories = [
            'Electrònica', 'Roba', 'Mobles', 'Llibres', 'Esports',
            'Joguines', 'Música', 'Cuina', 'Jardí', 'Vehicles'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();
            $category = $faker->randomElement($categories);

            $product->setTitle($category . ' - ' . $faker->words(3, true));
            $product->setDescription($faker->paragraph(3));
            $product->setPrice($faker->randomFloat(2, 1, 500));

            // Generem URLs d'imatges amb Picsum (imatges aleatòries)
            $product->setImage('https://picsum.photos/seed/' . $faker->word() . $i . '/400/300');

            // Data de creació aleatòria dels últims 30 dies
            $product->setCreatedAt($faker->dateTimeBetween('-30 days', 'now'));

            // Assignem un propietari aleatori
            $product->setOwner($faker->randomElement($users));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
