<?php

namespace App\DataFixtures;

use App\Entity\RepLog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->userData($manager);
        $this->repData($manager);
    }

    private function userData(ObjectManager $manager)
    {
        $users = [
            ['amir', 'amir', 'moshfegh', 'u1'],
            ['nika', 'nika', 'moshfegh', 'u2'],
            ['niki', 'niki', 'moshfegh', 'u3'],
            ['sara', 'sara', 'makhmali', 'u4'],
        ];

        foreach ($users as [$email, $firstname, $lastName, $ref]) {
            $user = new User();
            $user->setUsername($email)
                ->setPassword($this->userPasswordEncoder->encodePassword($user, '123'))
                ->setFirstName($firstname)
                ->setLastName($lastName)
                ->setRoles(['ROLE_ADMIN']);

            $this->addReference($ref, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function repData(ObjectManager $manager)
    {
        for ($j = 0; $j < 30; $j++) {
            $repLog = new RepLog();
            $user=$this->getReference(['u1', 'u2', 'u3', 'u4'][rand(0, 3)]);
            $items=array_rand($repLog->getThingsYouCanLift());

            $repLog->setAuthor($user);
            $repLog->setReps(rand(1, 30));
            $repLog->setItem($items);

            $manager->persist($repLog);
        }
        $manager->flush();
    }
}
