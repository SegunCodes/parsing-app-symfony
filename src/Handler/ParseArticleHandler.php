<?php

namespace App\Handler;

use App\Entity\Article;
use App\Message\ParseArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ParseArticleHandler implements MessageHandlerInterface
{
  public function __construct(private EntityManagerInterface $em, private ArticleRepository $articleRepo)
  {
  }

  public function __invoke(ParseArticle $message)
  {
    $articleCrawler = $message->getArticleCrawler();

    $title = $articleCrawler->filter('a > h2')->text();
    $description = $articleCrawler->filter('p')->last()->text();
    $picture = null;

    // some articles don't have a picture
    try {
      $picture = $articleCrawler->filter('a > div.lenta-image img')->last()->attr('src');
    } catch (Exception $e) {
    }

    $existingArticle = $this->articleRepo->findOneBy(['title' => $title]);
    if ($existingArticle) {
      $existingArticle->setTitle($title);
      $existingArticle->setDescription($description);
      $existingArticle->setPicture($picture);
      $existingArticle->setUpdated(new \DateTime());

      $this->em->flush();
      return;
    }

    $article = new Article();
    $article->setTitle($title);
    $article->setDescription($description);
    $article->setPicture($picture);
    $article->setDatePublished(new \DateTime());

    // getting the published date from the article page makes the parsing process slower
    // $articleUrl = $articleCrawler->filter('a')->eq(1)->attr('href');
    // $article->setDatePublished($this->getDatePublished($articleUrl));

    $this->em->persist($article);
    $this->em->flush();
  }

  private function getDatePublished(string $articleUrl)
  {
    $html = file_get_contents($articleUrl);

    $crawler = new Crawler($html);
    $datePublished = $crawler->filter("meta[itemprop='datePublished']")->first()->attr('content');
    return new \DateTime($datePublished);
  }
}
