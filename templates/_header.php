<?php use function App\e; ?>

<header class="sticky top-0 z-10 bg-white/30 backdrop-blur-sm">
  <div class="mx-auto box-content flex max-w-2xl px-4 py-4">
    <div class="flex w-full items-center justify-between">
      <p class="text-xl font-semibold">
        <a href="https://kanau.app" target="_blank" class="text-gray-900 hover:text-violet-700">Kanau</a>
      </p>
      <div class="flex items-center gap-3">
        <button
          id="langToggle"
          class="flex cursor-pointer items-center gap-1 rounded px-2 py-1 text-sm text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
        >
          <i class="fas fa-language h-4 w-4"></i>
          <span id="langLabel"><?= $lang === 'ja' ? 'EN' : '日本語' ?></span>
        </button>
        <div class="relative">
          <button
            id="shareButton"
            class="flex cursor-pointer items-center gap-1 rounded px-2 py-1 text-sm text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
          >
            <i class="fas fa-share-alt h-4 w-4"></i>
            <span><?= e($t['share']) ?></span>
          </button>
          <div id="shareMenu" class="absolute top-full right-0 z-50 mt-1 hidden min-w-48 rounded-lg border border-gray-200 bg-white shadow-lg">
            <div class="py-1">
              <button id="shareToX" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                <i class="fab fa-x-twitter h-4 w-4"></i>
                <span><?= e($t['shareToX']) ?></span>
              </button>
              <button id="copyToClipboard" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-copy h-4 w-4"></i>
                <span><?= e($t['copyToClipboard']) ?></span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<script>
(function() {
  const currentLang = '<?= $lang ?>';
  const titleAndUrlCopied = <?= json_encode($t['titleAndUrlCopied']) ?>;

  document.getElementById('langToggle')?.addEventListener('click', function() {
    const url = new URL(window.location);
    url.searchParams.set('lang', currentLang === 'ja' ? 'en' : 'ja');
    window.location.href = url.toString();
  });

  const shareButton = document.getElementById('shareButton');
  const shareMenu = document.getElementById('shareMenu');

  shareButton?.addEventListener('click', function(e) {
    e.stopPropagation();
    shareMenu?.classList.toggle('hidden');
  });

  document.addEventListener('click', function() {
    shareMenu?.classList.add('hidden');
  });

  document.getElementById('shareToX')?.addEventListener('click', function() {
    const text = document.title + '\n' + window.location.href;
    window.open('https://x.com/intent/tweet?text=' + encodeURIComponent(text), '_blank', 'noopener,noreferrer');
    shareMenu?.classList.add('hidden');
  });

  document.getElementById('copyToClipboard')?.addEventListener('click', async function() {
    const text = document.title + '\n' + window.location.href;
    try {
      await navigator.clipboard.writeText(text);
    } catch(e) {
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    }
    alert(titleAndUrlCopied);
    shareMenu?.classList.add('hidden');
  });
})();
</script>
