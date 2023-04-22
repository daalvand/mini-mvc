<?php

use Core\Contracts\DB\Migration;
use Core\DB\Schema\Table;

return new class implements Migration {
    public function up(): void
    {
        schema()->createTable('users', function (Table $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        schema()->dropTable('users');
    }
};
