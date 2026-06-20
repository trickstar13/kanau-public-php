<?php

declare(strict_types=1);

namespace App;

function e(string|null $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatText(?string $text): string
{
    if ($text === null || $text === '') {
        return '';
    }

    $formatted = e($text);
    $formatted = str_replace("\n", '<br>', $formatted);
    $formatted = preg_replace_callback(
        '/(https:\/\/[^\s<>&]+)/',
        function ($match) {
            $url = $match[1];
            $display = mb_strlen($url) > 30 ? mb_substr($url, 0, 30) . '...' : $url;
            return '<a href="' . $url . '" target="_blank" rel="nofollow noopener noreferrer" class="text-violet-700 hover:text-violet-800 underline" title="' . $url . '">' . $display . '</a>';
        },
        $formatted,
    );

    return $formatted;
}

function formatCurrency(string $currencyCode): string
{
    return match ($currencyCode) {
        'JPY' => '¥',
        'USD' => '$',
        default => $currencyCode,
    };
}

function formatPrice(float|int $price, string $currencyCode): string
{
    if ($currencyCode === 'JPY') {
        return number_format(floor($price));
    }
    return number_format($price, 2);
}

function calculateTotalTime(array $items): string
{
    $totalSeconds = 0;
    foreach ($items as $item) {
        $hours = $item['itemHours'] ?? 0;
        $minutes = $item['itemMinutes'] ?? 0;
        $seconds = $item['itemSeconds'] ?? 0;
        $totalSeconds += $hours * 3600 + $minutes * 60 + $seconds;
    }

    $h = intdiv($totalSeconds, 3600);
    $m = intdiv($totalSeconds % 3600, 60);
    $s = $totalSeconds % 60;

    if ($h > 0) {
        return sprintf('%d:%02d:%02d', $h, $m, $s);
    }
    if ($m > 0 || $s > 0) {
        return sprintf('%d:%02d', $m, $s);
    }
    return '';
}
