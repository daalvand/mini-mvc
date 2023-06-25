<?php

namespace Tests\Feature\Models;

use App\Models\Item;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ItemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_item(): void
    {
        // Create a new item
        $item = Item::create([
             'title'       => 'Test Item',
             'description' => 'This is a test item',
             'price'       => 10,
             'image'       => 'test.jpg',
        ]);

        // Assert that the item was created
        $this->assertDatabaseHas('items', [
             'id'          => $item->id,
             'title'       => 'Test Item',
             'description' => 'This is a test item',
             'price'       => 10,
             'image'       => 'test.jpg',
        ]);
    }

    public function test_update_item(): void
    {
        // Create a new item
        $item = Item::create([
             'title'       => 'Test Item',
             'description' => 'This is a test item',
             'price'       => 10,
             'image'       => 'test.jpg',
        ]);

        // Update the item
        $item->update([
             'title'       => 'Updated Test Item',
             'description' => 'This is an updated test item',
             'price'       => 20,
             'image'       => 'updated_test.jpg',
        ]);

        // Assert that the item was updated
        $this->assertDatabaseHas('items', [
             'id'          => $item->id,
             'title'       => 'Updated Test Item',
             'description' => 'This is an updated test item',
             'price'       => 20,
             'image'       => 'updated_test.jpg',
        ]);
    }

    public function test_find_item(): void
    {
        // Create a new item
        $item = Item::create([
             'title'       => 'Test Item',
             'description' => 'This is a test item',
             'price'       => 10,
             'image'       => 'test.jpg',
        ]);

        // Find the item
        $foundItem = Item::findOne($item->id);

        // Assert that the found item matches the created item
        $this->assertEquals($item->id, $foundItem->id);
        $this->assertEquals($item->title, $foundItem->title);
        $this->assertEquals($item->description, $foundItem->description);
        $this->assertEquals($item->price, $foundItem->price);
        $this->assertEquals($item->image, $foundItem->image);
    }

    public function test_delete_an_item(): void
    {
        $item = Item::create([
             'title'       => 'Item to delete',
             'description' => 'This is an item to delete',
             'price'       => 20,
             'image'       => 'https://example.com/image2.png',
        ]);

        Item::query()->where('id', $item->id)->delete();

        $this->assertDatabaseMissing('items', [
             'title'       => 'Item to delete',
             'description' => 'This is an item to delete',
             'price'       => 20,
             'image'       => 'https://example.com/image2.png',
        ]);
    }
}
