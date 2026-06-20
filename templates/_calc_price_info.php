<?php
use function App\e;
use function App\formatCurrency;
use function App\formatPrice;

$hasCalcInfo = ($item['price'] ?? 0) > 0
    || ($item['packagePrice'] ?? 0) > 0
    || ($item['unitPrice'] ?? 0) > 0
    || !empty($item['unitSize'])
    || ($item['packageUnitNum'] ?? 0) > 0;

if (!$hasCalcInfo) return;
?>
<div class="flex w-full items-baseline gap-1 text-xs text-gray-600">
  <i class="fa-solid fa-calculator"></i>
  <?php if (($item['segmentModel'] ?? '') === 'numPricing'): ?>
  <div class="flex flex-wrap items-center gap-x-2">
    <?php if (($item['price'] ?? 0) > 0): ?>
    <div><?= e($t['price']) ?>: <?= formatCurrency($currency) ?><?= formatPrice($item['price'] ?? 0, $currency) ?></div>
    <?php endif; ?>
    <?php if (!empty($item['itemQuantity'])): ?>
    <div><?= e($t['quantity']) ?>: <?= e((string)$item['itemQuantity']) ?></div>
    <?php endif; ?>
  </div>
  <?php elseif (($item['segmentModel'] ?? '') === 'unitPricing'): ?>
  <div class="flex flex-wrap items-center gap-x-2">
    <?php if (($item['packagePrice'] ?? 0) > 0): ?>
    <div><?= e($t['packagePrice']) ?>: <?= formatCurrency($currency) ?><?= formatPrice($item['packagePrice'] ?? 0, $currency) ?></div>
    <?php endif; ?>
    <?php if (!empty($item['unitSize'])): ?>
    <div><?= e($t['unitSize']) ?>: <?= e((string)$item['unitSize']) ?></div>
    <?php endif; ?>
  </div>
  <?php elseif (($item['segmentModel'] ?? '') === 'specificPricing'): ?>
  <div class="flex flex-wrap items-center gap-x-2">
    <?php if (($item['unitPrice'] ?? 0) > 0): ?>
    <div><?= e($t['unitPrice']) ?>: <?= formatCurrency($currency) ?><?= formatPrice($item['unitPrice'] ?? 0, $currency) ?></div>
    <?php endif; ?>
    <?php if (($item['packageUnitNum'] ?? 0) > 0): ?>
    <div><?= e($t['packageUnitNum']) ?>: <?= e((string)$item['packageUnitNum']) ?> <?= e($item['packageUnit'] ?? '') ?></div>
    <?php endif; ?>
  </div>
  <?php elseif (($item['segmentModel'] ?? '') === 'hourlyPricing'): ?>
  <div class="flex flex-wrap items-center gap-x-2">
    <?php if (($item['unitPrice'] ?? 0) > 0): ?>
    <div><?= e($t['price']) ?>: <?= formatCurrency($currency) ?><?= formatPrice($item['unitPrice'] ?? 0, $currency) ?></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
