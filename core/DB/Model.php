<?php

namespace Core\DB;

use ArrayAccess;

abstract class Model implements ArrayAccess
{

    protected static string $tableName;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    protected array $attributes = [];

    protected array $fillable = [];

    protected array $casts = [];

    /**
     * Get table name or create it
     *
     * @return string
     */
    public static function tableName(): string
    {
        if (isset(static::$tableName)) {
            return static::$tableName;
        }
        $exploded          = explode('\\', __CLASS__);
        static::$tableName = strtolower(end($exploded)) . 's';
        return static::$tableName;
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public static function create(array $attributes = []): static
    {
        $instance = new static($attributes);
        static::query()->insert($instance->attributes);
        return $instance;
    }

    public function update(array $data): bool
    {
        return static::query()->update($data);
    }

    public static function findOne(int $id): static|null
    {
        return static::query()->where(static::primaryKey(), '=', $id)->first();
    }

    private function fill(array $attributes): void
    {
        if (count($this->fillable) > 0) {
            $this->attributes = array_intersect_key($attributes, array_flip($this->fillable));
        }
    }

    public static function query(): ModelQueryBuilder
    {
        return (new ModelQueryBuilder(database()))->model(static::class);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }
}
