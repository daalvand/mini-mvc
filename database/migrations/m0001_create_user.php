<?php

use Core\Contracts\DB\Migration;

return new class implements Migration {
    public function up(): string
    {
        return "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                password VARCHAR(512) NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,                
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;";
    }

    public function down(): string
    {
        return "DROP TABLE users;";
    }
};