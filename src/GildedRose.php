<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private const MAX_QUALITY = 50;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if (str_starts_with($item->name, 'Sulfuras')) {
                continue;
            }

            $daysRemaining = $item->sellIn;
            $item->sellIn--;
            $passedSellBy = $item->sellIn < 0;

            if ($item->name === 'Aged Brie') {
                $item->quality += $passedSellBy ? 2 : 1;
            } elseif (str_starts_with($item->name, 'Backstage passes')) {
                if ($passedSellBy) {
                    $item->quality = 0;
                } elseif ($daysRemaining <= 5) {
                    $item->quality += 3;
                } elseif ($daysRemaining <= 10) {
                    $item->quality += 2;
                } else {
                    ++$item->quality;
                }
            } elseif (str_starts_with($item->name, 'Conjured')) {
                $item->quality -= $passedSellBy ? 4 : 2;
            } else {
                $item->quality -= $passedSellBy ? 2 : 1;
            }

            $item->quality = max(0, min(self::MAX_QUALITY, $item->quality));
        }
    }
}
