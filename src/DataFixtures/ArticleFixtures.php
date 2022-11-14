<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $article = new Article();
    $article->setTitle('Lorem ipsum');
    $article->setDescription('Article description');
    $article->setPicture('https://highload.today/wp-content/uploads/2022/10/Depositphotos_559389328_L.jpeg.webp');
    $article->setDatePublished(new \DateTime());
    $manager->persist($article);
    $manager->flush();

    $article2 = new Article();
    $article2->setTitle("Big Mac for Bitcoin: McDonald's uses a new payment method");
    $article->setDatePublished(new \DateTime());
    $article2->setDescription('In the popular fast food chain, it will be possible to pay not only with ordinary money, but also with two types of cryptocurrency, writes Cointelegraph. But not everywhere.');
    $article2->setPicture('https://highload.today/wp-content/uploads/2022/10/ukav.png.webp');
    $manager->persist($article2);
    $manager->flush();
  }
}
