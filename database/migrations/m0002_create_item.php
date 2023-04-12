<?php

use Core\Contracts\DB\Migration;

return new class implements Migration {
    public function up(): string
    {
        return "CREATE TABLE items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(1000) NOT NULL,
                image VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price INT NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;";
    }

    public function down(): string
    {
        return "DROP TABLE items;";
    }
};
