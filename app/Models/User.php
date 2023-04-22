<?php

namespace App\Models;

use Core\Contracts\DB\UserModel;

/**
 * @property int    id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 */
class User extends UserModel
{
    protected static string $tableName = 'users';
    protected array $fillable = ['first_name', 'last_name', 'email', 'password'];
    public function fullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
