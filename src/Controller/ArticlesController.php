<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
  private  $pageSize = 10;

  public function __construct(private ArticleRepository $articleRepo, private EntityManagerInterface $em)
  {
  }

  #[Route('/', name: 'articles', methods: ['GET'],)]
  #[Route('/articles', name: 'homepage', methods: ['GET'],)]
  public function index(Request $req): Response
  {
    $currentPage = max(1, $req->get('page', 1));
    $firstResult = ($currentPage - 1) * $this->pageSize;

    $criteria = [];
    $articles = $this->articleRepo->findBy($criteria, ['datePublished' => 'DESC'], $this->pageSize, $firstResult);
    $count = $this->articleRepo->count($criteria);
    $hasMore = $count > $currentPage * $this->pageSize;

    return $this->render('articles/index.html.twig', [
      'articles' => $articles,
      'count' => $count,
      'pageNum' => $currentPage,
      'pageSize' => $this->pageSize,
      'nextPage' => $hasMore ? $currentPage + 1 : null,
      'prevPage' => $currentPage > 1 ? $currentPage - 1 : null,
    ]);
  }

  #[Route('/articles/{id<\d+>}', name: 'article_delete', methods: ["DELETE", 'GET'])]
  public function delete(int $id): Response
  {
    if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
      return $this->redirectToRoute('articles');
    }

    $article = $this->articleRepo->find($id);
    if (!$article) {
      throw $this->createNotFoundException('Article not found');
    }

    $this->em->remove($article);
    $this->em->flush();

    return $this->redirectToRoute('articles');
  }
}
