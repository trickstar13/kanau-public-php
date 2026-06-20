<?php use function App\e; ?>

<div class="flex min-h-screen items-center justify-center bg-gray-50">
  <div class="mx-auto max-w-2xl px-4 text-center">
    <div class="rounded-lg bg-white p-8 shadow-sm">
      <div class="mb-6">
        <div class="mb-4 flex items-start justify-between">
          <h1 class="text-3xl font-bold text-gray-900">Kanau</h1>
          <button
            onclick="switchLanguage()"
            class="flex items-center gap-1 rounded px-2 py-1 text-sm text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
          >
            <i class="fas fa-language h-4 w-4"></i>
            <span><?= $lang === 'ja' ? 'EN' : '日本語' ?></span>
          </button>
        </div>
        <p class="mb-6 text-lg text-gray-600">
          <?= $lang === 'ja' ? 'プロジェクト共有プラットフォーム' : 'Project Sharing Platform' ?>
        </p>
        <p class="leading-relaxed text-gray-500">
          <?= $lang === 'ja'
            ? 'Kanauで作成されたプロジェクトを公開・共有できるプラットフォームです。<br>タスク管理、時間管理、価格管理を一元化し、効率的なプロジェクト運営をサポートします。'
            : 'A platform for sharing and publishing projects created with Kanau.<br>Centralize task management, time management, and pricing for efficient project operations.' ?>
        </p>
      </div>

      <div class="flex flex-col gap-4">
        <a
          href="https://kanau.app"
          target="_blank"
          class="inline-flex items-center justify-center gap-2 rounded-lg bg-violet-500 px-6 py-3 font-medium text-white transition-colors hover:bg-violet-600"
        >
          <span><?= e($t['startKanau']) ?></span>
          <i class="fas fa-rocket h-5 w-5"></i>
        </a>
        <p class="text-sm text-gray-500">
          <?= $lang === 'ja'
            ? 'プロジェクトを表示するには、URLに直接アクセスしてください。<br>例: /project/your-project-id'
            : 'To view a project, access the URL directly.<br>Example: /project/your-project-id' ?>
        </p>
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
