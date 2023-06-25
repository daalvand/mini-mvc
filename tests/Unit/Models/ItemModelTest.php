<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use Tests\TestCase;

class ItemModelTest extends TestCase
{
    /**
     * @dataProvider itemDataProvider
     */
    public function test_properties(Item $item): void
    {
        $this->assertEquals('Test Item', $item->title);
        $this->assertEquals('This is a test item', $item->description);
        $this->assertEquals(100, $item->price);
        $this->assertEquals('test.jpg', $item->image);
    }

     /**
      * @dataProvider itemDataProvider
      */
    public function test_fillable(Item $item): void
    {
        $fillable = ['title', 'description', 'price', 'image'];
        $this->assertEquals($fillable, $item->getFillable());
    }

     /**
      * @dataProvider itemDataProvider
      */
    public function test_table_name(Item $item): void
    {
        $this->assertEquals('items', $item->tableName());
    }

    //item dataProvider
    public static function itemDataProvider(): array
    {
        return [
             [
                  new Item([
                       'title'       => 'Test Item',
                       'description' => 'This is a test item',
                       'price'       => 100,
                       'image'       => 'test.jpg',
                  ]),
             ],
        ];
    }
}
