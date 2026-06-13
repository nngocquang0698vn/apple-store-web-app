<?php

namespace App\Support;

use DOMDocument;
use DOMElement;

final class ProductDescriptionSanitizer
{
    /** @var list<string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'h2', 'h3', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a',
        'img', 'div', 'iframe', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    /** @var list<string> */
    private const REMOVED_TAGS = [
        'script', 'style', 'object', 'embed',
    ];

    /** @var array<string, list<string>> */
    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'div' => ['class'],
        'iframe' => ['src', 'title', 'allow', 'allowfullscreen'],
        'th' => ['colspan', 'rowspan'],
        'td' => ['colspan', 'rowspan'],
    ];

    public static function prepare(?string $input): ?string
    {
        if ($input === null || trim($input) === '') {
            return null;
        }

        $input = self::normalizePastedSpacing(trim($input));
        $input = ProductDescriptionYoutube::convertLinksToEmbeds($input);

        return self::sanitize($input);
    }

    private static function normalizePastedSpacing(string $input): string
    {
        $input = str_replace("\xC2\xA0", ' ', $input);
        $input = preg_replace('/&nbsp;/i', ' ', $input) ?? $input;
        $input = preg_replace('/\x{200B}/u', '', $input) ?? $input;

        return $input;
    }

    public static function sanitize(?string $input): ?string
    {
        if ($input === null || trim($input) === '') {
            return null;
        }

        $input = trim($input);

        if ($input === strip_tags($input)) {
            return '<p>'.nl2br(e($input), false).'</p>';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div>'.$input.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $container = $document->getElementsByTagName('div')->item(0);

        if (! $container instanceof DOMElement) {
            return null;
        }

        self::sanitizeElementTree($container);

        $html = '';

        foreach ($container->childNodes as $child) {
            $html .= $document->saveHTML($child);
        }

        $html = trim($html);

        if ($html === '') {
            return null;
        }

        return self::wrapResponsiveTables($html);
    }

    private static function wrapResponsiveTables(string $html): string
    {
        if (! str_contains($html, '<table')) {
            return $html;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div>'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $container = $document->getElementsByTagName('div')->item(0);

        if (! $container instanceof DOMElement) {
            return $html;
        }

        $tables = [];

        foreach ($container->getElementsByTagName('table') as $table) {
            if ($table instanceof DOMElement) {
                $tables[] = $table;
            }
        }

        foreach ($tables as $table) {
            $parent = $table->parentNode;

            if ($parent === null) {
                continue;
            }

            if ($parent instanceof DOMElement
                && $parent->tagName === 'div'
                && $parent->getAttribute('class') === 'product-description-table-wrap') {
                continue;
            }

            $wrapper = $document->createElement('div');
            $wrapper->setAttribute('class', 'product-description-table-wrap');
            $parent->insertBefore($wrapper, $table);
            $wrapper->appendChild($table);
        }

        $wrapped = '';

        foreach ($container->childNodes as $child) {
            $wrapped .= $document->saveHTML($child);
        }

        return trim($wrapped);
    }

    private static function sanitizeElementTree(DOMElement $parent): void
    {
        $node = $parent->firstChild;

        while ($node !== null) {
            if ($node instanceof DOMElement) {
                $tag = strtolower($node->tagName);

                if (in_array($tag, self::REMOVED_TAGS, true)) {
                    $parent->removeChild($node);
                    $node = $parent->firstChild;

                    continue;
                }

                if ($tag === 'div' && $node->getAttribute('class') !== 'video-embed') {
                    self::unwrapNode($node);
                    $node = $parent->firstChild;

                    continue;
                }

                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    self::unwrapNode($node);
                    $node = $parent->firstChild;

                    continue;
                }

                if (! self::isAllowedElement($node)) {
                    $parent->removeChild($node);
                    $node = $parent->firstChild;

                    continue;
                }

                self::sanitizeAttributes($node);
                self::sanitizeElementTree($node);
            }

            $node = $node->nextSibling;
        }
    }

    private static function isAllowedElement(DOMElement $element): bool
    {
        $tag = strtolower($element->tagName);

        if ($tag === 'iframe') {
            return self::isAllowedIframe($element);
        }

        if ($tag === 'img') {
            return self::isAllowedImage($element);
        }

        return true;
    }

    private static function unwrapNode(DOMElement $node): void
    {
        $parent = $node->parentNode;

        if ($parent === null) {
            return;
        }

        while ($node->firstChild !== null) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private static function sanitizeAttributes(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

        if ($element->hasAttributes()) {
            $attributeNames = [];

            foreach ($element->attributes as $attribute) {
                $attributeNames[] = $attribute->name;
            }

            foreach ($attributeNames as $name) {
                $lowerName = strtolower($name);

                if (str_starts_with($lowerName, 'on') || ! in_array($lowerName, $allowed, true)) {
                    $element->removeAttribute($name);
                }
            }
        }

        if ($tag === 'a') {
            self::sanitizeAnchor($element);
        }

        if ($tag === 'img') {
            self::sanitizeImage($element);
        }

        if ($tag === 'iframe') {
            self::sanitizeIframe($element);
        }
    }

    private static function sanitizeAnchor(DOMElement $anchor): void
    {
        $href = trim((string) $anchor->getAttribute('href'));

        if ($href === '' || ! self::isSafeHref($href)) {
            self::unwrapNode($anchor);

            return;
        }

        $anchor->setAttribute('href', $href);

        $target = strtolower(trim((string) $anchor->getAttribute('target')));

        if ($target === '_blank') {
            $anchor->setAttribute('target', '_blank');
            $anchor->setAttribute('rel', 'noopener noreferrer');
        } else {
            $anchor->removeAttribute('target');
            $anchor->removeAttribute('rel');
        }
    }

    private static function sanitizeImage(DOMElement $image): void
    {
        if (! self::isAllowedImage($image)) {
            $image->parentNode?->removeChild($image);

            return;
        }

        $src = trim((string) $image->getAttribute('src'));
        $image->setAttribute('src', ProductImageUrl::rewriteStorageSrc($src));
    }

    private static function sanitizeIframe(DOMElement $iframe): void
    {
        if (! self::isAllowedIframe($iframe)) {
            $iframe->parentNode?->removeChild($iframe);
        }
    }

    private static function isAllowedImage(DOMElement $image): bool
    {
        $src = trim((string) $image->getAttribute('src'));

        if ($src === '' || str_starts_with(strtolower($src), 'data:')) {
            return false;
        }

        if (str_starts_with($src, '/storage/')) {
            return true;
        }

        $path = parse_url($src, PHP_URL_PATH);

        return is_string($path) && str_starts_with($path, '/storage/');
    }

    private static function isAllowedIframe(DOMElement $iframe): bool
    {
        $src = trim((string) $iframe->getAttribute('src'));

        if ($src === '') {
            return false;
        }

        return ProductDescriptionYoutube::extractVideoId($src) !== null;
    }

    private static function isSafeHref(string $href): bool
    {
        $lowerHref = strtolower($href);

        if (str_starts_with($lowerHref, 'javascript:')
            || str_starts_with($lowerHref, 'data:')
            || str_starts_with($lowerHref, 'vbscript:')) {
            return false;
        }

        return str_starts_with($lowerHref, 'http://')
            || str_starts_with($lowerHref, 'https://')
            || str_starts_with($lowerHref, 'mailto:')
            || str_starts_with($href, '/')
            || str_starts_with($href, '#');
    }
}
