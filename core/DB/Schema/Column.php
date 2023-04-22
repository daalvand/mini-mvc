<?php

namespace Core\DB\Schema;

class Column
{
    protected bool    $nullable      = false;
    protected bool    $primaryKey    = false;
    protected bool    $autoIncrement = false;
    protected bool    $unique        = false;
    protected ?int    $length        = null;
    protected ?int    $precision     = null;
    protected ?int    $scale         = null;
    protected ?string $charset       = null;
    protected ?string $collation     = null;
    protected mixed   $default       = null;

    public function __construct(
         protected string $name,
         protected string $type
    ) {
    }

    public function default(mixed $default): static
    {
        $this->default = $default;
        return $this;
    }

    public function nullable(bool $value = true): static
    {
        $this->nullable = $value;
        return $this;
    }

    public function primaryKey(bool $value = true): static
    {
        $this->primaryKey = $value;
        return $this;
    }

    public function autoIncrement(bool $value = true): static
    {
        $this->autoIncrement = $value;
        return $this;
    }

    public function unique(bool $value = true): static
    {
        $this->unique = $value;
        return $this;
    }

    public function length(int $value): static
    {
        $this->length = $value;
        return $this;
    }

    public function precision(int $value): static
    {
        $this->precision = $value;
        return $this;
    }

    public function scale(int $value): static
    {
        $this->scale = $value;
        return $this;
    }

    public function charset(string $value): static
    {
        $this->charset = $value;
        return $this;
    }

    public function collation(string $value): static
    {
        $this->collation = $value;
        return $this;
    }

    public function __toString(): string
    {
        $sql = "`$this->name` $this->type";

        if ($this->length !== null) {
            $sql .= "($this->length)";
        }

        if ($this->precision !== null && $this->scale !== null) {
            $sql .= "($this->precision, $this->scale)";
        }

        if ($this->charset !== null) {
            $sql .= " CHARACTER SET $this->charset";
        }

        if ($this->collation !== null) {
            $sql .= " COLLATE $this->collation";
        }

        if (!$this->nullable) {
            $sql .= ' NOT NULL';
        }

        if ($this->primaryKey) {
            $sql .= ' PRIMARY KEY';
        }

        if ($this->autoIncrement) {
            $sql .= ' AUTO_INCREMENT';
        }

        if ($this->unique) {
            $sql .= ' UNIQUE';
        }

        if ($this->default !== null) {
            if (is_string($this->default)) {
                $this->default = "'$this->default'";
            } elseif (is_bool($this->default)) {
                $this->default = (int)$this->default;
            }
            $sql .= " DEFAULT $this->default";
        }

        return $sql;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toSql(): string
    {
        return (string)$this;
    }
}
