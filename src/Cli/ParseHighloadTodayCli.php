<?php

namespace App\Cli;

use App\Message\ParseArticle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
  name: 'app:parse-highload.today',
  description: 'Parses highload.today articles by category',
)]
class ParseHighloadTodayCli extends Command
{
  public function __construct(private MessageBusInterface $bus)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this
      ->addArgument('category', InputArgument::OPTIONAL, 'Article category to parse', 'novosti');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $category = $input->getArgument('category');
    $url = "https://highload.today/category/" . $category;
    $html = file_get_contents($url);

    $crawler = new Crawler($html);

    $articles = $crawler->filter('div.col.sidebar-center > div.lenta-item');
    $len = $articles->count();

    for ($i = 1; $i < $len; $i++) {
      $article = $articles->eq($i);
      $this->bus->dispatch(new ParseArticle($article->html()));
    }

    return Command::SUCCESS;
  }
}
