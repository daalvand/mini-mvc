<?php

namespace Core\Contracts\DB;

abstract class UserModel extends Model
{
    abstract public function fullName(): string;
}