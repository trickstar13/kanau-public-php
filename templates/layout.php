<?php
use function App\e;
$ogImage = ($image ?? null) ?: 'https://public.kanau.app/default-image.jpg';
$url = $url ?? '';
$type = $type ?? 'website';
?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
  <link rel="icon" type="image/png" href="/favicon.png">
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">

  <meta property="og:title" content="<?= e($title) ?>">
  <meta property="og:description" content="<?= e($description) ?>">
  <meta property="og:type" content="<?= e($type) ?>">
  <?php if ($url): ?><meta property="og:url" content="<?= e($url) ?>"><?php endif; ?>
  <meta property="og:site_name" content="Kanau">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:image:alt" content="<?= e($title) ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($title) ?>">
  <meta name="twitter:description" content="<?= e($description) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <meta name="robots" content="index, follow">
  <?php if ($url): ?><link rel="canonical" href="<?= e($url) ?>"><?php endif; ?>
</head>
<body class="min-h-screen bg-gray-50">
  <?= $content ?>
</body>
</html>
