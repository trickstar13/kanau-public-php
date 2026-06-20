<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Cache\FileCache;
use App\I18n;
use App\Services\FirestoreService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = AppFactory::create();

$cache = new FileCache(__DIR__ . '/../var/cache');
$firestore = new FirestoreService(
    $_ENV['FIREBASE_PROJECT_ID'] ?? 'kanau-environment',
    $_ENV['FIREBASE_API_KEY'] ?? '',
    $cache,
);

function render(string $template, array $data = []): string
{
    extract($data);
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    return ob_get_clean();
}

function renderPage(string $template, array $data, array $layoutData): string
{
    $content = render($template, $data);
    return render('layout', array_merge($layoutData, ['content' => $content]));
}

function detectLanguage(Request $request): string
{
    $queryLang = $request->getQueryParams()['lang'] ?? null;
    if ($queryLang && in_array($queryLang, ['ja', 'en'], true)) {
        return $queryLang;
    }
    $accept = $request->getHeaderLine('Accept-Language');
    return I18n::detectLanguage($accept);
}

// Homepage
$app->get('/', function (Request $request, Response $response) {
    $lang = detectLanguage($request);
    $t = I18n::get($lang);

    $html = renderPage('home', [
        'lang' => $lang,
        't' => $t,
    ], [
        'title' => $lang === 'ja'
            ? 'Kanau - プロジェクト共有プラットフォーム'
            : 'Kanau - Project Sharing Platform',
        'description' => $lang === 'ja'
            ? 'Kanauで作成されたプロジェクトを公開・共有できるプラットフォームです。タスク管理、時間管理、価格管理を一元化し、効率的なプロジェクト運営をサポートします。'
            : 'A platform for sharing and publishing projects created with Kanau. Centralize task management, time management, and pricing for efficient project operations.',
        'lang' => $lang,
        'type' => 'website',
    ]);

    $response->getBody()->write($html);
    return $response;
});

// Project page
$app->get('/project/{id}', function (Request $request, Response $response, array $args) use ($firestore) {
    $id = $args['id'];
    $lang = detectLanguage($request);
    $t = I18n::get($lang);

    $project = $firestore->getProject($id);

    if (!$project || ($project['isVisible'] ?? true) === false) {
        return render404($request, $response, $lang, $t);
    }

    $profile = null;
    if (!empty($project['userId'])) {
        $profile = $firestore->getProfile($project['userId']);
    }

    $items = $project['items'] ?? [];
    usort($items, fn($a, $b) => ($a['order'] ?? 0) - ($b['order'] ?? 0));

    $itemCount = count($items);
    $checkedItems = array_filter($items, fn($i) => !empty($i['isDone']));
    $uncheckedItems = array_filter($items, fn($i) => empty($i['isDone']));
    $checkedItemCount = count($checkedItems);
    $notCheckedItemCount = $itemCount - $checkedItemCount;

    $totalPrice = array_sum(array_map(fn($i) => $i['price'] ?? 0, $items));
    $donePrice = array_sum(array_map(fn($i) => $i['price'] ?? 0, $checkedItems));
    $undonePrice = $totalPrice - $donePrice;

    $totalTime = \App\calculateTotalTime($items);
    $doneTime = \App\calculateTotalTime(array_values($checkedItems));
    $undoneTime = \App\calculateTotalTime(array_values($uncheckedItems));

    $currency = $profile['currency'] ?? '$';

    $ogTitle = !empty($profile['displayName'])
        ? "{$project['title']} | {$profile['displayName']} | Kanau"
        : "{$project['title']} | Kanau";

    $desc = $project['description'] ?? '';
    $descFlat = str_replace("\n", ' ', $desc);
    if (mb_strlen($descFlat) > 160) {
        $ogDescription = mb_substr($descFlat, 0, 160) . '...';
    } elseif ($descFlat !== '') {
        $ogDescription = $descFlat;
    } else {
        $displayName = $profile['displayName'] ?? ($lang === 'ja' ? '匿名ユーザー' : 'Anonymous User');
        $ogDescription = "{$displayName}が作成したプロジェクト（{$itemCount}件のアイテム）";
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $ogUrl = "{$scheme}://{$host}/project/{$id}";

    $html = renderPage('project', [
        'project' => $project,
        'profile' => $profile,
        'lang' => $lang,
        't' => $t,
        'sortedItems' => $items,
        'itemCount' => $itemCount,
        'checkedItemCount' => $checkedItemCount,
        'notCheckedItemCount' => $notCheckedItemCount,
        'totalPrice' => $totalPrice,
        'donePrice' => $donePrice,
        'undonePrice' => $undonePrice,
        'totalTime' => $totalTime,
        'doneTime' => $doneTime,
        'undoneTime' => $undoneTime,
        'currency' => $currency,
    ], [
        'title' => $ogTitle,
        'description' => $ogDescription,
        'image' => $project['photoDataUrl'] ?? null,
        'url' => $ogUrl,
        'lang' => $lang,
        'type' => 'article',
    ]);

    $response->getBody()->write($html);
    return $response;
});

function render404(Request $request, Response $response, string $lang, array $t): Response
{
    $html = renderPage('404', [
        'lang' => $lang,
        't' => $t,
    ], [
        'title' => $t['pageNotFoundTitle'] . ' | Kanau',
        'description' => $t['pageNotFoundDescription'],
        'lang' => $lang,
        'type' => 'website',
    ]);

    $response->getBody()->write($html);
    return $response->withStatus(404);
}

// Catch-all 404
$app->map(['GET'], '/{routes:.+}', function (Request $request, Response $response) {
    $lang = detectLanguage($request);
    $t = I18n::get($lang);
    return render404($request, $response, $lang, $t);
});

$app->run();
