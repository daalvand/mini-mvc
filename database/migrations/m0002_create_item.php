<?php

use Core\Contracts\DB\Migration;
use Core\DB\Schema\Table;

return new class implements Migration {
    public function up(): void
    {
        schema()->createTable('items', function (Table $table) {
            $table->id();
            $table->string('title', 1000);
            $table->string('image');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        schema()->dropTable('items');
    }
};
