<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\User;
use App\Entity\Meeting;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MeetingFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory ::create('fr_FR');

        for($i = 1; $i <= 25; $i++){
            $meeting = new Meeting();
            $creator = $this->getReference('user_' . $faker->numberBetween(1,20));
            $meeting->setCreator($creator);
            $meeting->setAgenda($faker->sentence(6));
            $meeting->setTitle($faker->text(20));
            $time = $faker->time();
            $timeImmutable = new \DateTimeImmutable($time);
            $meeting->setTime($timeImmutable);
            $dateTime = $faker->dateTimeThisMonth();
            $dateTimeImmutable = \DateTimeImmutable::createFromMutable($dateTime);
            $meeting->setDate($dateTimeImmutable);
         
            $numberOfParticipants = $faker->numberBetween(1, 8);
        
            for ($j =0; $j<$numberOfParticipants; $j++){
                $participant = $this->getReference('user_' . $faker->numberBetween(1, 10));
                $meeting->addParticipant($participant);
            }
            $manager->persist($meeting);

        }    

        $manager->flush();
    }

    public function getDependencies(){

        return[
            UserFixtures::class,
        ];
    }

}
