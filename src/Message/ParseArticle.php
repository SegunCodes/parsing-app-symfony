<?php

namespace App\Message;

use Symfony\Component\DomCrawler\Crawler;

class ParseArticle
{
  public function __construct(private string $article)
  {
  }

  public function getArticleCrawler(): Crawler
  {
    return new Crawler($this->article);
  }
}
