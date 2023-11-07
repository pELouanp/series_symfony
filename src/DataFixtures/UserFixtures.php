<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@series.com');
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Elouan');
        $admin->setLastName('Péron');

        $manager->persist($admin);

        $john = new User();
        $john->setEmail('john@doe.fr');
        $john->setPassword($this->hasher->hashPassword($john, '1234'));
        $john->setFirstName('John');
        $john->setLastName('Doe');

        $manager->persist($john);
        $manager->flush();
    }
}
