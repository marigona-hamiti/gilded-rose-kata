<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    public function testNormalItemDegrades(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('+5 Dexterity Vest', 0, 20),
            new Item('+5 Dexterity Vest', 0, 0),
        ];

        (new GildedRose($items))->updateQuality();

        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(19, $items[0]->quality);
        $this->assertSame(18, $items[1]->quality);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testAgedBrieIncreasesInQuality(): void
    {
        $items = [
            new Item('Aged Brie', 5, 10),
            new Item('Aged Brie', 0, 10),
            new Item('Aged Brie', 5, 50),
        ];

        (new GildedRose($items))->updateQuality();

        $this->assertSame(11, $items[0]->quality);
        $this->assertSame(12, $items[1]->quality);
        $this->assertSame(50, $items[2]->quality);
    }

    public function testSulfurasNeverChanges(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];

        (new GildedRose($items))->updateQuality();

        $this->assertSame(0, $items[0]->sellIn);
        $this->assertSame(80, $items[0]->quality);
    }

    public function testBackstagePasses(): void
    {
        $name = 'Backstage passes to a TAFKAL80ETC concert';

        $items = [
            new Item($name, 11, 20),
            new Item($name, 10, 20),
            new Item($name, 5, 20),
            new Item($name, 0, 20),
        ];

        (new GildedRose($items))->updateQuality();

        $this->assertSame(21, $items[0]->quality);
        $this->assertSame(22, $items[1]->quality);
        $this->assertSame(23, $items[2]->quality);
        $this->assertSame(0, $items[3]->quality);
    }

    public function testConjuredItemsDegradeTwiceAsFast(): void
    {
        $items = [
            new Item('Conjured Mana Cake', 3, 6),
            new Item('Conjured Mana Cake', 0, 10),
        ];

        (new GildedRose($items))->updateQuality();

        $this->assertSame(4, $items[0]->quality);
        $this->assertSame(6, $items[1]->quality);
    }
}
