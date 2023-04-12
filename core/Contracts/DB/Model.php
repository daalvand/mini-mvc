<?php

namespace Core\Contracts\DB;

use Core\App;
use PDO;
use PDOStatement;

abstract class Model
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
        $instance->save();
        return $instance;
    }

    public function save(): bool
    {
        $tableName          = static::tableName();
        $attributes         = $this->attributes;
        $params             = array_map(static fn($attr) => ":$attr", array_keys($attributes));
        $implodedAttributes = implode(", ", array_keys($attributes));
        $implodedParams     = implode(", ", $params);

        $statement = self::prepare("INSERT INTO $tableName ($implodedAttributes) VALUES ($implodedParams)");
        foreach ($attributes as $attribute => $value) {
            $statement->bindValue(":$attribute", $value, $this->casts[$attribute] ?? PDO::PARAM_STR);
        }
        $statement->execute();
        return true;
    }

    public static function prepare($sql): PDOStatement
    {
        return App::getInstance()->get(Database::class)->prepare($sql);
    }

    public static function findOne($where): static|null
    {
        $rows = static::paginate(where: $where, perPage: 1);
        return reset($rows['data']) ?: null;
    }

    public static function paginate(array $where, array $orderBys = [], int $page = 1, int $perPage = 10): array
    {
        $tableName  = static::tableName();
        $attributes = array_keys($where);
        $whereSql   = implode("AND", array_map(static fn($attr) => "$attr = :$attr", $attributes));

        if (!isset($orderBys[self::primaryKey()])) {
            $orderBys[self::primaryKey()] = "ASC";
        }

        $orderSqlArray = [];
        foreach ($orderBys as $attr => $dir) {
            $dir             = strtoupper($dir) === "DESC" ? "DESC" : "ASC";
            $orderSqlArray[] = "$attr $dir";
        }

        $orderSql = implode(", ", $orderSqlArray);

        $offset    = (max($page, 0) - 1) * $perPage;
        if($whereSql){
            $queryString = "SELECT * FROM $tableName WHERE $whereSql ORDER BY $orderSql LIMIT $perPage OFFSET $offset";
        }else{
            $queryString = "SELECT * FROM $tableName ORDER BY $orderSql LIMIT $perPage OFFSET $offset";
        }
        $statement = self::prepare($queryString);
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $statement->fetchAll();
        $data = [];
        foreach ($rows as $row) {
            $instance = new static();
            foreach ($row as $name => $value) {
                $instance->{$name} = $value;
            }
            $data[] = $instance;
        }

        if($whereSql){
            $statement = self::prepare("SELECT count(*) as count FROM $tableName WHERE $whereSql");
        }else{
            $statement = self::prepare("SELECT count(*) as count FROM $tableName");
        }
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $total = $statement->fetch()['count'];
        return [
             'data' => $data,
             'meta' => [
                  'page'      => $page,
                  'per_page'  => $perPage,
                  'total'     => $total,
                  'last_page' => intdiv($total, $perPage) + ($total % $perPage),
             ],
        ];
    }

    private function fill(array $attributes): void
    {
        if (count($this->fillable) > 0) {
            $this->attributes = array_intersect_key($attributes, array_flip($this->fillable));
        }
    }
}
