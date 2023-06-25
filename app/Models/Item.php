<?php

namespace App\Models;


use Core\DB\Model;

/**
 * @property int id
 * @property string title
 * @property string description
 * @property int price
 * @property string image
 */
class Item extends Model
{
    protected static string $tableName = 'items';

    protected array $fillable = ['title', 'description', 'price', 'image'];
}
