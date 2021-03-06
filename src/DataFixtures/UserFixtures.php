<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

function formatName(string $nameToFormat): String
{
    return strtolower(strtr(iconv("UTF-8", "ASCII//TRANSLIT", $nameToFormat), ' ', '-'));
}

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i < 100; $i++) {

            $user = new User();

            $firstName = $faker->firstName();
            $lastName = $faker->lastName();

            $user->setFirstName($firstName);
            $user->setLastName($lastName);

            $firstName = formatName($firstName);
            $lastName = formatName($lastName);

            $username = $firstName[0] . $lastName;
            $email = "{$firstName}.{$lastName}@centrale-marseille.fr";

            $roles = ['ROLE_USER'];
            if($i == 1) {
                $roles[] = 'ROLE_ADMIN';
            }

            $user->setUsername($username)
                 ->setEmail($email)
                 ->setPassword($this->passwordEncoder->encodePassword(
                     $user,
                     $username . '123'
                 ))
                 ->setScore(0)
                 ->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
