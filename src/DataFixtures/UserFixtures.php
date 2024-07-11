<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;
use App\Entity\User;


class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void{
    
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $user = new User;
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->hashPassword($user,'azerty'));
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);

        }    

        $manager->flush();
    }
}
