<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class NewsController extends Controller
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Article::published($page, 6, $q);

        $this->view('news/index', [
            'title'       => setting('seo_news_title', __('news.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_news_description', __('news.subtitle')),
            'result'      => $result,
            'q'           => $q,
        ]);
    }

    public function show(string $slug): void
    {
        $article = Article::findBySlug($slug);
        if (!$article || empty($article['is_published'])) {
            not_found_page();
        }
        $this->view('news/show', [
            'title'       => $article['seo_title'] ?: ($article['title'] . ' | ' . setting('company_name')),
            'description' => $article['seo_description'] ?: $article['excerpt'],
            'article'     => $article,
            'bodyHtml'    => Article::bodyToHtml((string)$article['body']),
            'latest'      => Article::latest(3),
        ]);
    }
}
