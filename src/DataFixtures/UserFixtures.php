<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $passwordHasher)
  {
  }

  public function load(ObjectManager $manager): void
  {
    $admin = new User();
    $admin->setEmail('admin@highload.co');
    $admin->setRoles(['ROLE_ADMIN']);

    $admin->setPassword(
      $this->passwordHasher->hashPassword(
        $admin,
        'password'
      )
    );
    $manager->persist($admin);
    $manager->flush();

    $moderator = new User();
    $moderator->setEmail('moderator@highload.co');
    $moderator->setRoles(['ROLE_MODERATOR']);

    $moderator->setPassword(
      $this->passwordHasher->hashPassword(
        $moderator,
        'password'
      )
    );
    $manager->persist($moderator);
    $manager->flush();
  }
}
