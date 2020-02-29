<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Children;
use App\Entity\Event;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker      = Factory::create('Fr-fr');

        // Ici on gère les Roles

        $ownerRole = new Role();
        $ownerRole ->setTitle('ROLE_OWNER');
        $manager->persist($ownerRole);

        $ownerUser = new User();
        $ownerUser ->setFirstName('Euratech')
            ->setLastName('Admin')
            ->setEmail('euratech@gmail.com')
            ->setHash($this->encoder->encodePassword($ownerUser,'password'))
            ->setCity('Lille')
            ->setPhone($faker->phoneNumber)
            ->addUserRole($ownerRole);
        $manager->persist($ownerUser);

        $adminRole  = new Role();
        $adminRole  ->setTitle('ROLE_ADMIN');
        $manager    ->persist($adminRole);

        $adminUser = new User();
        $adminUser  ->setFirstName('Julien')
                    ->setLastName('Grade')
                    ->setEmail('gradejulien@gmail.com')
                    ->setHash($this->encoder->encodePassword($adminUser,'password'))
                    ->setCity($faker->city)
                    ->setPhone($faker->phoneNumber)
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Ici on gère les utilisateurs

        $users  = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i ++){
            $user   = new User();

            $genre  = $faker->randomElement($genres);

            $hash   = $this->encoder->encodePassword($user, 'password');

            $user   ->setFirstName($faker->firstname($genre))
                    ->setLastName($faker->lastname)
                    ->setEmail($faker->email)
                    ->setHash($hash)
                    ->setCity($faker->city)
                    ->setPhone($faker->phoneNumber);

            $manager->persist($user);

            $users[] = $user;

        }

        // Ici on gère les événements

        $categories = ['enfants', 'parents/enfants'];
        for($i=1; $i<=5; $i++)
        {
            $event          = new Event();

            $title          = $faker->sentence();
            $description    = $faker->paragraph(2);
            $picture        = $faker->creditCardNumber . '.' . $faker->fileExtension;
            $category       = $faker->randomElement($categories);

            $event  ->setTitle($title)
                    ->setCategory($category)
                    ->setStartDate($faker->dateTimeBetween('-6 months'))
                    ->setEndDate($faker->dateTimeBetween('-3 months'))
                    ->setSeats(mt_rand(5,35))
                    ->setPicture($picture)
                    ->setDescription($description)
                    ->setAgeMin(mt_rand(4,8))
                    ->setAgeMax(mt_rand(8,13))
                    ->setLocation('LearningDistrict à Euratechnologie Lille');

            for($j=1 ; $j <= mt_rand(0, 10); $j++) {
                $booking    = new Booking();

                $booker     = $users[mt_rand(0, count($users) - 1)];

                $comment    = $faker->paragraph();

                $booking    ->setBooker($booker)
                            ->setComment($comment)
                            ->setEvent($event);

                $sexes = ['garçon', 'fille'];
                for($c = 1 ; $c <= mt_rand(1,4); $c++){
                    $children = new Children();
                    $sexe = $faker->randomElement($sexes);

                    $children->setSexe($sexe)
                             ->setAge(mt_rand(4, 12))
                             ->setFirstName($faker->firstname)
                             ->setLastName($faker->lastname)
                             ->setBooking($booking);
                    $manager->persist($children);
                }
                $manager->persist($booking);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
}
