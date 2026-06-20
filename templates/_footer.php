<?php use function App\e; ?>

<footer class="mt-8 p-6 text-center md:mt-10">
  <div class="flex flex-col items-center gap-3">
    <div class="flex items-center gap-1 text-sm text-gray-600">
      <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
      </svg>
      <span><?= e($t['poweredBy']) ?></span>
    </div>
    <?php if (!empty($showStartButton)): ?>
    <a
      href="https://kanau.app"
      target="_blank"
      class="inline-flex items-center gap-2 rounded border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
    >
      <span><?= e($t['startKanau']) ?></span>
      <i class="fas fa-rocket h-4 w-4"></i>
    </a>
    <?php endif; ?>
  </div>
</footer>
