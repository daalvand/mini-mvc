<?php

namespace Core\Contracts\DB;

interface Migration
{
    public function up();

    public function down();
}
