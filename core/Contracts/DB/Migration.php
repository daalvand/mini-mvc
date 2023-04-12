<?php

namespace Core\Contracts\DB;

interface Migration
{
    public function up(): string;

    public function down(): string;
}
