<?php

namespace Core\DB\Schema;

class Table
{
    /** @var Column[] */
    protected array $columns = [];

    public function __construct(protected string $name)
    {
    }

    public function column(string $name, string $type): Column
    {
        $column          = new Column($name, $type);
        $this->columns[] = $column;
        return $column;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function id(string $name = 'id'): Column
    {
        return $this->unsignedInteger($name)->primaryKey()->autoIncrement();
    }

    public function string(string $name, int $length = 255): Column
    {
        return $this->column($name, "VARCHAR($length)");
    }

    public function text(string $name): Column
    {
        return $this->column($name, 'TEXT');
    }

    public function timestamp(string $name): Column
    {
        return $this->column($name, 'TIMESTAMP');
    }

    public function timestampCurrent(string $name): Column
    {
        return $this->column($name, 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }

    public function timestamps(): void
    {
        $this->timestamp('updated_at')->nullable();
        $this->timestamp('created_at')->nullable();
    }

    public function timestampsCurrent(): void
    {
        $this->timestampCurrent('updated_at');
        $this->timestampCurrent('created_at');
    }

    public function integer(string $name): Column
    {
        return $this->column($name, 'INT(11)');
    }

    public function smallInteger(string $name): Column
    {
        return $this->column($name, 'SMALLINT');
    }

    public function bigInteger(string $name): Column
    {
        return $this->column($name, 'BIGINT');
    }

    public function unsignedInteger(string $name): Column
    {
        return $this->column($name, 'INT(11) UNSIGNED');
    }

    public function unsignedBigInteger(string $name): Column
    {
        return $this->column($name, 'BIGINT UNSIGNED');
    }

    public function unsignedSmallInteger(string $name): Column
    {
        return $this->column($name, 'SMALLINT UNSIGNED');
    }

    public function unsignedTinyInteger(string $name): Column
    {
        return $this->column($name, 'TINYINT UNSIGNED');
    }

    public function float(string $name): Column
    {
        return $this->column($name, 'FLOAT');
    }

    public function double(string $name): Column
    {
        return $this->column($name, 'DOUBLE');
    }

    public function decimal(string $name, int $precision = 8, int $scale = 2): Column
    {
        return $this->column($name, "DECIMAL($precision, $scale)");
    }

    public function boolean(string $name): Column
    {
        return $this->column($name, 'TINYINT(1)');
    }

    public function date(string $name): Column
    {
        return $this->column($name, 'DATE');
    }

    public function time(string $name): Column
    {
        return $this->column($name, 'TIME');
    }

    public function dateTime(string $name): Column
    {
        return $this->column($name, 'DATETIME');
    }

    public function binary(string $name): Column
    {
        return $this->column($name, 'BLOB');
    }
}
