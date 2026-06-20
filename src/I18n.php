<?php

declare(strict_types=1);

namespace App;

class I18n
{
    public static function detectLanguage(string $acceptLanguage): string
    {
        if ($acceptLanguage === '') {
            return 'ja';
        }

        $languages = array_map(
            fn($l) => strtolower(trim(explode(';', $l)[0])),
            explode(',', $acceptLanguage),
        );

        foreach ($languages as $lang) {
            if (str_starts_with($lang, 'ja')) {
                return 'ja';
            }
        }

        return 'en';
    }

    public static function get(string $lang): array
    {
        return match ($lang) {
            'en' => self::en(),
            default => self::ja(),
        };
    }

    public static function all(): array
    {
        return [
            'ja' => self::ja(),
            'en' => self::en(),
        ];
    }

    private static function ja(): array
    {
        return [
            'share' => '共有',
            'by' => 'by',
            'publishedOn' => '公開日',
            'updatedOn' => '更新日',
            'notChecked' => '未完了',
            'checked' => '完了',
            'total' => '合計',
            'gridView' => 'グリッド表示',
            'listView' => 'リスト表示',
            'expandAll' => 'すべて展開',
            'closeAll' => 'すべて閉じる',
            'quantity' => '数量',
            'time' => '時間',
            'price' => '価格',
            'openLink' => 'リンクを開く',
            'packagePrice' => '販売価格',
            'unitSize' => '入り数',
            'unitPrice' => '販売単価',
            'packageUnitNum' => '販売単位',
            'close' => '閉じる',
            'status' => '状態',
            'checkedStatus' => '完了',
            'notCheckedStatus' => '未完了',
            'viewImage' => '画像を表示',
            'contact' => '連絡する',
            'anonymousUser' => '匿名ユーザー',
            'noItems' => 'アイテムがありません',
            'noItemsDesc' => 'このプロジェクトにはまだアイテムが追加されていません。',
            'poweredBy' => 'Powered by Kanau',
            'startKanau' => 'Kanauを始める',
            'shareToX' => 'Xに投稿',
            'copyToClipboard' => 'クリップボードにコピー',
            'titleAndUrlCopied' => 'タイトルとURLをクリップボードにコピーしました',
            'urlCopied' => 'URLをクリップボードにコピーしました',
            'projectLoadFailed' => 'プロジェクトの読み込みに失敗しました',
            'projectNotFoundOrStopped' => 'プロジェクトが見つかりません。公開が停止されているか、所有者の課金が停止されている可能性があります。',
            'pageNotFoundTitle' => 'ページが見つかりません',
            'pageNotFoundDescription' => 'お探しのページは見つかりませんでした。',
            'checkUrlOrContact' => 'URLを確認するか、プロジェクトの所有者にお問い合わせください。',
            'backToHome' => 'ホームに戻る',
        ];
    }

    private static function en(): array
    {
        return [
            'share' => 'Share',
            'by' => 'by',
            'publishedOn' => 'Published',
            'updatedOn' => 'Updated',
            'notChecked' => 'Not Checked',
            'checked' => 'Checked',
            'total' => 'Total',
            'gridView' => 'Grid View',
            'listView' => 'List View',
            'expandAll' => 'Expand All',
            'closeAll' => 'Close All',
            'quantity' => 'Quantity',
            'time' => 'Time',
            'price' => 'Price',
            'openLink' => 'Open Link',
            'packagePrice' => 'Package Price',
            'unitSize' => 'Quantity per Package',
            'unitPrice' => 'Package Price',
            'packageUnitNum' => 'Package Unit',
            'close' => 'Close',
            'status' => 'Status',
            'checkedStatus' => 'Checked',
            'notCheckedStatus' => 'Not Checked',
            'viewImage' => 'View Image',
            'contact' => 'Contact',
            'anonymousUser' => 'Anonymous User',
            'noItems' => 'No Items',
            'noItemsDesc' => 'No items have been added to this project yet.',
            'poweredBy' => 'Powered by Kanau',
            'startKanau' => 'Start Kanau',
            'shareToX' => 'Share on X',
            'copyToClipboard' => 'Copy to Clipboard',
            'titleAndUrlCopied' => 'Title and URL copied to clipboard',
            'urlCopied' => 'URL copied to clipboard',
            'projectLoadFailed' => 'Failed to load project',
            'projectNotFoundOrStopped' => 'Project not found. Publishing may have been stopped or the owner\'s subscription may have expired.',
            'pageNotFoundTitle' => 'Page Not Found',
            'pageNotFoundDescription' => 'The page you are looking for could not be found.',
            'checkUrlOrContact' => 'Please check the URL or contact the project owner.',
            'backToHome' => 'Back to Home',
        ];
    }
}
