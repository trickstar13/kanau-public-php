<?php
use function App\e;
use function App\formatText;
use function App\formatCurrency;
use function App\formatPrice;
?>

<?php include __DIR__ . '/_header.php'; ?>

<main class="mx-auto max-w-2xl">
  <!-- Project info section -->
  <section class="bg-white">
    <?php if (!empty($project['photoDataUrl'])): ?>
    <div class="relative">
      <img
        src="<?= e($project['photoDataUrl']) ?>"
        alt="<?= e($project['title']) ?>"
        class="h-64 w-full object-cover"
        id="projectImage"
      >
      <button
        id="viewImageBtn"
        class="absolute right-4 bottom-4 flex size-8 cursor-pointer items-center justify-center rounded-sm border border-white bg-white/50 text-white transition-all hover:bg-white/80"
        aria-label="<?= e($t['viewImage'] ?? '画像を表示') ?>"
      >
        <i class="fas fa-image text-base text-gray-700"></i>
      </button>
    </div>
    <?php endif; ?>

    <div class="p-8">
      <div class="mb-4 flex items-center gap-2">
        <h1 class="text-2xl font-medium text-gray-900"><?= e($project['title']) ?></h1>
      </div>

      <?php if (!empty($project['description'])): ?>
      <div class="mb-4">
        <div class="leading-relaxed"><?= formatText($project['description']) ?></div>
      </div>
      <?php endif; ?>

      <div class="mb-4 text-sm text-gray-500">
        <?= e($t['publishedOn']) ?>: <?= date($lang === 'ja' ? 'Y/m/d' : 'M j, Y', intval($project['publishedAt'] / 1000)) ?>
        <?php if (($project['updatedAt'] ?? 0) !== ($project['publishedAt'] ?? 0)): ?>
        <span class="ml-4">
          <?= e($t['updatedOn']) ?>: <?= date($lang === 'ja' ? 'Y/m/d' : 'M j, Y', intval($project['updatedAt'] / 1000)) ?>
        </span>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- View toggle controls -->
  <?php if ($itemCount > 0): ?>
  <div class="border-t border-neutral-100 bg-white px-4 py-2">
    <div class="flex justify-end gap-2">
      <button
        id="toggleExpandBtn"
        class="flex cursor-pointer items-center gap-1 px-3 py-2 text-sm text-gray-600 hover:text-gray-900"
        style="<?= !empty($project['isGridView']) ? 'display:none' : 'display:flex' ?>"
      >
        <i id="expandIcon" class="fas fa-chevron-down h-4 w-4"></i>
        <span id="expandLabel"><?= e($t['expandAll'] ?? 'すべて開く') ?></span>
      </button>
      <button
        id="toggleViewBtn"
        class="flex cursor-pointer items-center gap-1 px-3 py-2 text-sm text-gray-600 hover:text-gray-900"
      >
        <i id="viewIcon" class="shrink-0 <?= !empty($project['isGridView']) ? 'fas fa-list' : 'fas fa-th' ?>"></i>
        <span id="viewLabel"><?= e(!empty($project['isGridView']) ? $t['listView'] : $t['gridView']) ?></span>
      </button>
    </div>
  </div>
  <?php endif; ?>

  <!-- Item list -->
  <?php if ($itemCount > 0): ?>
  <section class="bg-white">
    <!-- List view -->
    <div id="listView" class="space-y-4 p-4" style="<?= !empty($project['isGridView']) ? 'display:none' : '' ?>">
      <?php foreach ($sortedItems as $item): ?>
      <div class="overflow-hidden bg-white shadow-lg shadow-neutral-100">
        <details open class="flex flex-col p-4">
          <summary class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
              <div class="flex-shrink-0">
                <?php if (!empty($item['isDone'])): ?>
                <i class="fa-solid fa-circle-check text-xl text-violet-700"></i>
                <?php else: ?>
                <i class="fa-regular fa-circle text-xl text-gray-400"></i>
                <?php endif; ?>
              </div>
              <h2 class="shrink text-lg/[1.3] font-medium text-gray-900"><?= e($item['title']) ?></h2>
            </div>
            <div class="flex shrink-0 justify-end gap-4 text-sm">
              <?php if (isset($item['itemQuantity']) && $item['itemQuantity'] !== 0): ?>
              <span><?= e((string)$item['itemQuantity']) ?><?php if (!empty($item['packageUnit'])): ?><?= e($item['packageUnit']) ?><?php endif; ?></span>
              <?php endif; ?>
              <?php if (($item['itemHours'] ?? 0) > 0 || ($item['itemMinutes'] ?? 0) > 0 || ($item['itemSeconds'] ?? 0) > 0): ?>
              <span class="flex">
                <span><?= $item['itemHours'] ?? '00' ?></span>
                <span>:</span>
                <span><?= str_pad((string)($item['itemMinutes'] ?? 0), 2, '0', STR_PAD_LEFT) ?></span>
                <span>:</span>
                <span><?= str_pad((string)($item['itemSeconds'] ?? 0), 2, '0', STR_PAD_LEFT) ?></span>
              </span>
              <?php endif; ?>
              <?php if (isset($item['price']) && $item['price'] > 0): ?>
              <span><?= formatCurrency($currency) ?><?= formatPrice($item['price'], $currency) ?></span>
              <?php endif; ?>
            </div>
          </summary>
          <div class="mt-4 flex items-start gap-3 md:mt-6">
            <?php if (!empty($item['imageUrl'])): ?>
            <button class="flex-shrink-0 cursor-pointer" onclick="openItemModal('<?= e($item['id']) ?>')">
              <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" class="h-16 w-16 rounded-sm object-contain">
            </button>
            <?php endif; ?>

            <div class="[margin-top:calc((1em-1lh)/2)] flex min-w-0 flex-1 flex-col gap-4 md:gap-6">
              <?php if (!empty($item['description'])): ?>
              <p class="text-sm/[1.6]"><?= formatText($item['description']) ?></p>
              <?php endif; ?>

              <?php include __DIR__ . '/_calc_price_info.php'; ?>
            </div>
          </div>
          <?php if (!empty($item['url'])): ?>
          <div class="mt-4 flex items-center justify-end gap-3">
            <div class="flex-shrink-0">
              <a href="<?= e($item['url']) ?>" target="_blank" rel="nofollow noopener noreferrer" class="text-sm text-violet-600 hover:text-violet-700" aria-label="<?= e($t['openLink']) ?>">
                <?= e($t['openLink']) ?>
                <i class="fas fa-external-link-alt h-5 w-5"></i>
              </a>
            </div>
          </div>
          <?php endif; ?>
        </details>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Grid view -->
    <div id="gridView" class="grid grid-cols-3 gap-4 p-4" style="<?= !empty($project['isGridView']) ? 'display:grid' : 'display:none' ?>">
      <?php foreach ($sortedItems as $item): ?>
      <div class="relative aspect-square overflow-hidden bg-white shadow-lg shadow-neutral-100">
        <div class="absolute top-1 left-1 z-20">
          <?php if (!empty($item['isDone'])): ?>
          <i class="fa-solid fa-circle-check aspect-square rounded-full text-xl text-violet-700"></i>
          <?php else: ?>
          <i class="fa-regular fa-circle rounded-full text-xl text-gray-600"></i>
          <?php endif; ?>
        </div>
        <div class="h-full w-full cursor-pointer transition-opacity hover:opacity-80" onclick="openItemModal('<?= e($item['id']) ?>')">
          <?php if (!empty($item['imageUrl'])): ?>
          <img src="<?= e($item['imageUrl']) ?>" alt="<?= e($item['title']) ?>" class="h-full w-full object-contain">
          <?php else: ?>
          <div class="flex h-full w-full items-center justify-center overflow-hidden p-2 text-center text-xs"><?= e($item['title']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Empty state -->
  <?php if ($itemCount === 0): ?>
  <section class="bg-white p-8">
    <div class="text-center">
      <div class="mb-4 text-gray-400">
        <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"/>
        </svg>
      </div>
      <h2 class="mb-2 text-lg font-medium text-gray-900"><?= e($t['noItems']) ?></h2>
      <p class="text-gray-600"><?= e($t['noItemsDesc']) ?></p>
    </div>
  </section>
  <?php endif; ?>

  <!-- Statistics table -->
  <?php if ($itemCount > 0): ?>
  <div class="bg-white p-8">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-200">
          <th class="py-2 text-left font-medium text-gray-900"></th>
          <th class="py-2 text-right font-medium text-gray-900"><?= e($t['quantity']) ?></th>
          <?php if ($undoneTime || $doneTime || $totalTime): ?>
          <th class="py-2 text-right font-medium text-gray-900"><?= e($t['time']) ?></th>
          <?php endif; ?>
          <?php if ($undonePrice > 0 || $donePrice > 0 || $totalPrice > 0): ?>
          <th class="py-2 text-right font-medium text-gray-900"><?= e($t['price']) ?></th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="py-2"><?= e($t['notChecked']) ?></td>
          <td class="py-2 text-right"><?= $notCheckedItemCount ?></td>
          <?php if ($undoneTime || $doneTime || $totalTime): ?>
          <td class="py-2 text-right"><?= $undoneTime ?: '-' ?></td>
          <?php endif; ?>
          <?php if ($undonePrice > 0 || $donePrice > 0 || $totalPrice > 0): ?>
          <td class="py-2 text-right"><?= $undonePrice > 0 ? formatCurrency($currency) . formatPrice($undonePrice, $currency) : '-' ?></td>
          <?php endif; ?>
        </tr>
        <tr class="border-b border-gray-200">
          <td class="py-2"><?= e($t['checked']) ?></td>
          <td class="py-2 text-right"><?= $checkedItemCount ?></td>
          <?php if ($undoneTime || $doneTime || $totalTime): ?>
          <td class="py-2 text-right"><?= $doneTime ?: '-' ?></td>
          <?php endif; ?>
          <?php if ($undonePrice > 0 || $donePrice > 0 || $totalPrice > 0): ?>
          <td class="py-2 text-right"><?= $donePrice > 0 ? formatCurrency($currency) . formatPrice($donePrice, $currency) : '-' ?></td>
          <?php endif; ?>
        </tr>
        <tr>
          <td class="py-2"><?= e($t['total']) ?></td>
          <td class="py-2 text-right"><?= $itemCount ?></td>
          <?php if ($undoneTime || $doneTime || $totalTime): ?>
          <td class="py-2 text-right"><?= $totalTime ?: '-' ?></td>
          <?php endif; ?>
          <?php if ($undonePrice > 0 || $donePrice > 0 || $totalPrice > 0): ?>
          <td class="py-2 text-right"><?= $totalPrice > 0 ? formatCurrency($currency) . formatPrice($totalPrice, $currency) : '-' ?></td>
          <?php endif; ?>
        </tr>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <!-- User profile -->
  <?php if (!empty($project['userId'])): ?>
  <section class="bg-white pb-2">
    <div class="p-4">
      <div class="flex items-start gap-3">
        <?php if (!empty($profile['photoDataUrl'])): ?>
        <img src="<?= e($profile['photoDataUrl']) ?>" alt="<?= e($profile['displayName'] ?? 'User') ?>" class="h-12 w-12 rounded-full object-cover">
        <?php else: ?>
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-200">
          <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <?php endif; ?>
        <div class="flex-1">
          <h2 class="mb-1 font-medium text-gray-900">
            <?= e($profile['displayName'] ?? $t['anonymousUser']) ?>
            <span class="mb-2 text-[10px] text-gray-500">(<?= e($project['userId']) ?>)</span>
          </h2>
          <?php if (!empty($profile['contactUrl'])): ?>
          <a href="<?= e($profile['contactUrl']) ?>" target="_blank" rel="nofollow noopener noreferrer" class="inline-flex items-center gap-1 text-sm text-violet-700 hover:text-violet-800 hover:underline">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span><?= e($t['contact']) ?></span>
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php $showStartButton = true; include __DIR__ . '/_footer.php'; ?>
</main>

<!-- Project image modal -->
<?php if (!empty($project['photoDataUrl'])): ?>
<div id="projectImageModal" class="fixed inset-0 z-50 hidden bg-black/90">
  <div class="flex min-h-[100dvh] items-center justify-center p-4">
    <div class="relative max-h-[90dvh] max-w-[90vw]">
      <button id="closeProjectImageModal" class="absolute -top-12 right-0 text-white hover:text-gray-300" aria-label="<?= e($t['close'] ?? '閉じる') ?>">
        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      <img id="modalProjectImage" src="<?= e($project['photoDataUrl']) ?>" alt="<?= e($project['title']) ?>" class="max-h-[85dvh] max-w-full object-contain">
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Item detail modal -->
<div id="itemModal" class="fixed inset-0 z-50 hidden bg-black/50">
  <div class="flex min-h-[100dvh] items-center justify-center p-4">
    <div class="max-h-[90dvh] w-full max-w-lg overflow-y-auto bg-white">
      <div class="sticky top-0 flex items-center justify-between border-b bg-white/30 p-4 backdrop-blur-sm">
        <h2 id="modalTitle" class="text-lg font-semibold"></h2>
        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-4">
        <div id="modalStatus" class="mb-4 flex items-center gap-2">
          <div id="modalCheckIcon" class="h-5 w-5"></div>
          <span id="modalStatusText" class="text-sm font-medium"></span>
        </div>
        <div id="modalImageContainer" class="mb-4 hidden">
          <img id="modalImage" class="w-full" alt="">
        </div>
        <div id="modalDescriptionContainer" class="mb-4 hidden">
          <p id="modalDescription"></p>
        </div>
        <div id="modalMeta" class="mb-4 flex flex-wrap items-center gap-2 text-sm text-gray-600">
          <div id="modalTime" class="hidden"><span id="modalTimeValue"></span></div>
          <div id="modalQuantity" class="hidden"><span id="modalQuantityValue"></span></div>
          <div id="modalPrice" class="hidden"><span id="modalPriceValue"></span></div>
        </div>
        <div id="modalPriceInfo" class="mb-4"></div>
        <div id="modalUrlContainer" class="mt-4 flex justify-end">
          <a id="modalUrl" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded bg-violet-500 px-4 py-2 text-white transition-colors hover:bg-violet-600">
            <span id="modalUrlText"><?= e($t['openLink']) ?></span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  var items = <?= json_encode(array_values($sortedItems), JSON_UNESCAPED_UNICODE) ?>;
  var currency = <?= json_encode($currency) ?>;
  var initialGridView = <?= json_encode(!empty($project['isGridView'])) ?>;
  var translations = <?= json_encode($t, JSON_UNESCAPED_UNICODE) ?>;
  var currentLang = <?= json_encode($lang) ?>;
  var allTranslations = <?= json_encode(\App\I18n::all(), JSON_UNESCAPED_UNICODE) ?>;

  var projectItems = {};
  items.forEach(function(item) { projectItems[item.id] = item; });

  var currentLanguage = currentLang;

  function formatText(text) {
    if (!text) return '';
    var formatted = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    formatted = formatted.replace(/\n/g, '<br>');
    formatted = formatted.replace(/(https:\/\/[^\s<>&]+)/g, function(match) {
      var display = match.length > 30 ? match.substring(0, 30) + '...' : match;
      return '<a href="' + match + '" target="_blank" rel="nofollow noopener noreferrer" class="text-violet-700 hover:text-violet-800 underline" title="' + match + '">' + display + '</a>';
    });
    return formatted;
  }

  function formatCurrency(c) {
    if (c === 'JPY') return '¥';
    if (c === 'USD') return '$';
    return c;
  }

  function formatPrice(price, c) {
    if (c === 'JPY') return Math.floor(price).toLocaleString();
    return price.toLocaleString();
  }

  function generatePriceInfoHTML(item, currency, t) {
    if (!((item.price ?? 0) > 0 || (item.packagePrice ?? 0) > 0 || (item.unitPrice ?? 0) > 0 || item.unitSize || item.packageUnitNum || item.packageUnit)) return '';
    var html = '<div class="flex w-full flex-wrap items-center gap-1 text-xs text-gray-600"><i class="fa-solid fa-calculator"></i>';
    if (item.segmentModel === 'numPricing') {
      html += '<div class="flex flex-wrap items-center gap-2">';
      if ((item.price ?? 0) > 0) html += '<div>' + t.price + ': ' + formatCurrency(currency) + formatPrice(item.price, currency) + '</div>';
      if (item.itemQuantity) html += '<div>' + t.quantity + ': ' + item.itemQuantity + '</div>';
      html += '</div>';
    }
    if (item.segmentModel === 'unitPricing') {
      html += '<div class="flex flex-wrap items-center gap-2">';
      if ((item.packagePrice ?? 0) > 0) html += '<div>' + t.packagePrice + ': ' + formatCurrency(currency) + formatPrice(item.packagePrice, currency) + '</div>';
      if (item.unitSize) html += '<div>' + t.unitSize + ': ' + item.unitSize + '</div>';
      html += '</div>';
    }
    if (item.segmentModel === 'specificPricing') {
      html += '<div class="flex flex-wrap items-center gap-2">';
      if ((item.unitPrice ?? 0) > 0) html += '<div>' + t.unitPrice + ': ' + formatCurrency(currency) + formatPrice(item.unitPrice, currency) + '</div>';
      if ((item.packageUnitNum ?? 0) > 0) html += '<div>' + t.packageUnitNum + ': ' + item.packageUnitNum + ' ' + (item.packageUnit || '') + '</div>';
      html += '</div>';
    }
    if (item.segmentModel === 'hourlyPricing') {
      html += '<div class="flex flex-wrap items-center gap-2">';
      if ((item.unitPrice ?? 0) > 0) html += '<div>' + t.price + ': ' + formatCurrency(currency) + formatPrice(item.unitPrice, currency) + '</div>';
      html += '</div>';
    }
    html += '</div>';
    return html;
  }

  // View toggle
  var isGridView = initialGridView;
  var toggleViewBtn = document.getElementById('toggleViewBtn');
  var viewIcon = document.getElementById('viewIcon');
  var viewLabel = document.getElementById('viewLabel');
  var listView = document.getElementById('listView');
  var gridView = document.getElementById('gridView');
  var toggleExpandBtn = document.getElementById('toggleExpandBtn');
  var expandIcon = document.getElementById('expandIcon');
  var expandLabel = document.getElementById('expandLabel');

  toggleViewBtn?.addEventListener('click', function() {
    isGridView = !isGridView;
    if (isGridView) {
      listView.style.display = 'none';
      gridView.style.display = 'grid';
      viewLabel.textContent = translations.listView;
      viewIcon.className = 'fas fa-list h-4 w-4';
      if (toggleExpandBtn) toggleExpandBtn.style.display = 'none';
    } else {
      listView.style.display = 'block';
      gridView.style.display = 'none';
      viewLabel.textContent = translations.gridView;
      viewIcon.className = 'fas fa-th h-4 w-4';
      if (toggleExpandBtn) toggleExpandBtn.style.display = 'flex';
    }
  });

  var allExpanded = true;

  function toggleExpandAll() {
    var allDetails = document.querySelectorAll('#listView details');
    allExpanded = !allExpanded;
    allDetails.forEach(function(d) { d.open = allExpanded; });
    if (allExpanded) {
      expandIcon.className = 'fas fa-chevron-up h-4 w-4';
      expandLabel.textContent = translations.closeAll || 'すべて閉じる';
    } else {
      expandIcon.className = 'fas fa-chevron-down h-4 w-4';
      expandLabel.textContent = translations.expandAll || 'すべて開く';
    }
  }

  toggleExpandBtn?.addEventListener('click', toggleExpandAll);

  function checkExpandState() {
    var allDetails = document.querySelectorAll('#listView details');
    var openCount = Array.from(allDetails).filter(function(d) { return d.open; }).length;
    if (openCount === allDetails.length && allDetails.length > 0) {
      allExpanded = true;
      expandIcon.className = 'fas fa-chevron-up h-4 w-4';
      expandLabel.textContent = translations.closeAll || 'すべて閉じる';
    } else if (openCount === 0) {
      allExpanded = false;
      expandIcon.className = 'fas fa-chevron-down h-4 w-4';
      expandLabel.textContent = translations.expandAll || 'すべて開く';
    }
  }

  listView?.addEventListener('toggle', checkExpandState);
  checkExpandState();

  // Item modal
  var modal = document.getElementById('itemModal');
  var closeModalBtn = document.getElementById('closeModal');

  window.openItemModal = function(itemId) {
    var item = projectItems[itemId];
    if (!item) return;

    var ct = allTranslations[currentLanguage];

    document.getElementById('modalTitle').textContent = item.title;

    var checkIcon = document.getElementById('modalCheckIcon');
    var statusText = document.getElementById('modalStatusText');
    if (item.isDone) {
      checkIcon.className = 'fa-solid fa-circle-check text-violet-700 text-xl';
      statusText.textContent = ct.checkedStatus;
      statusText.className = 'text-sm font-medium';
    } else {
      checkIcon.className = 'fa-regular fa-circle text-gray-400 text-xl';
      statusText.textContent = ct.notCheckedStatus;
      statusText.className = 'text-sm font-medium text-gray-500';
    }

    var imageContainer = document.getElementById('modalImageContainer');
    var image = document.getElementById('modalImage');
    if (item.imageUrl) {
      image.src = item.imageUrl;
      image.alt = item.title;
      imageContainer.classList.remove('hidden');
    } else {
      imageContainer.classList.add('hidden');
    }

    var descContainer = document.getElementById('modalDescriptionContainer');
    var desc = document.getElementById('modalDescription');
    if (item.description) {
      desc.innerHTML = formatText(item.description);
      descContainer.classList.remove('hidden');
    } else {
      descContainer.classList.add('hidden');
    }

    document.getElementById('modalUrlText').textContent = ct.openLink;

    var priceContainer = document.getElementById('modalPrice');
    var priceValue = document.getElementById('modalPriceValue');
    if (item.price !== undefined && item.price > 0) {
      priceValue.textContent = formatCurrency(currency) + formatPrice(item.price, currency);
      priceContainer.classList.remove('hidden');
    } else {
      priceContainer.classList.add('hidden');
    }

    var quantityContainer = document.getElementById('modalQuantity');
    var quantityValue = document.getElementById('modalQuantityValue');
    if (item.itemQuantity !== undefined && item.itemQuantity !== 0) {
      quantityValue.textContent = item.itemQuantity + (item.packageUnit ? ' ' + item.packageUnit : '');
      quantityContainer.classList.remove('hidden');
    } else {
      quantityContainer.classList.add('hidden');
    }

    var timeContainer = document.getElementById('modalTime');
    var timeValue = document.getElementById('modalTimeValue');
    if (item.itemHours || item.itemMinutes || item.itemSeconds) {
      timeValue.textContent = (item.itemHours || 0) + ':' + String(item.itemMinutes || 0).padStart(2, '0') + ':' + String(item.itemSeconds || 0).padStart(2, '0');
      timeContainer.classList.remove('hidden');
    } else {
      timeContainer.classList.add('hidden');
    }

    document.getElementById('modalPriceInfo').innerHTML = generatePriceInfoHTML(item, currency, ct);

    var urlContainer = document.getElementById('modalUrlContainer');
    var urlLink = document.getElementById('modalUrl');
    if (item.url) {
      urlLink.href = item.url;
      urlContainer.classList.remove('hidden');
    } else {
      urlContainer.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  };

  function closeItemModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  closeModalBtn?.addEventListener('click', closeItemModal);
  modal?.addEventListener('click', function(e) {
    if (e.target === modal) closeItemModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeItemModal();
  });

  // Project image modal
  var projectImageModal = document.getElementById('projectImageModal');
  var viewImageBtn = document.getElementById('viewImageBtn');
  var closeProjectImageModalBtn = document.getElementById('closeProjectImageModal');

  viewImageBtn?.addEventListener('click', function() {
    projectImageModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  });

  function closeProjectImageModal() {
    projectImageModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  closeProjectImageModalBtn?.addEventListener('click', closeProjectImageModal);
  projectImageModal?.addEventListener('click', function(e) {
    if (e.target === projectImageModal) closeProjectImageModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && projectImageModal && !projectImageModal.classList.contains('hidden')) closeProjectImageModal();
  });
})();
</script>
