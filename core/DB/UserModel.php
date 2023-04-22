<?php

namespace Core\DB;

abstract class UserModel extends Model
{
    abstract public function fullName(): string;
}
