<?php use function App\e; ?>

<div class="flex min-h-screen items-center justify-center bg-gray-50">
  <div class="mx-auto max-w-2xl px-4 text-center">
    <div class="rounded-lg bg-white p-8 shadow-lg shadow-neutral-100">
      <div class="mb-6">
        <div class="mb-4 flex items-start justify-between">
          <h1 class="text-2xl font-bold text-gray-900">
            <a href="/" class="hover:text-violet-700">Kanau</a>
          </h1>
          <button
            onclick="switchLanguage()"
            class="flex items-center gap-1 rounded px-2 py-1 text-sm text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
          >
            <i class="fas fa-language h-4 w-4"></i>
            <span><?= $lang === 'ja' ? 'EN' : '日本語' ?></span>
          </button>
        </div>

        <h2 class="mb-4 text-3xl font-bold text-gray-900">404</h2>
        <p class="mb-6 text-left text-xl text-gray-600"><?= $t['projectNotFoundOrStopped'] ?></p>
        <p class="mb-6 text-left leading-relaxed text-gray-500"><?= e($t['checkUrlOrContact']) ?></p>
      </div>

      <div class="flex flex-col gap-4">
        <a
          href="https://kanau.app"
          target="_blank"
          class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-6 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-50"
        >
          <span><?= e($t['startKanau']) ?></span>
          <i class="fas fa-rocket h-5 w-5"></i>
        </a>
      </div>
    </div>

    <?php include __DIR__ . '/_footer.php'; ?>
  </div>
</div>

<script>
function switchLanguage() {
  const newLang = '<?= $lang ?>' === 'ja' ? 'en' : 'ja';
  const url = new URL(window.location);
  url.searchParams.set('lang', newLang);
  window.location.href = url.toString();
}
</script>
