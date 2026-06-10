<?php

namespace App\Support;

final class ProductDescriptionYoutube
{
    public static function extractVideoId(string $url): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        if (preg_match('~^https?://(?:www\.)?youtube\.com/embed/([A-Za-z0-9_-]{11})~i', $url, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('~^https?://(?:www\.)?youtube\.com/watch\?[^#]*v=([A-Za-z0-9_-]{11})~i', $url, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('~^https?://youtu\.be/([A-Za-z0-9_-]{11})~i', $url, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('~^https?://(?:www\.)?youtube\.com/shorts/([A-Za-z0-9_-]{11})~i', $url, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    public static function embedUrl(string $videoId): string
    {
        return 'https://www.youtube.com/embed/'.$videoId;
    }

    public static function embedHtml(string $videoId, string $title = 'Video YouTube'): string
    {
        $src = e(self::embedUrl($videoId));
        $safeTitle = e($title);

        return '<div class="video-embed"><iframe src="'.$src.'" title="'.$safeTitle.'" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
    }

    public static function convertLinksToEmbeds(string $html): string
    {
        return (string) preg_replace_callback(
            '/<a[^>]+href=["\']([^"\']+)["\'][^>]*>.*?<\/a>/is',
            static function (array $matches): string {
                $videoId = self::extractVideoId($matches[1]);

                return $videoId !== null
                    ? self::embedHtml($videoId)
                    : $matches[0];
            },
            $html,
        );
    }
}
