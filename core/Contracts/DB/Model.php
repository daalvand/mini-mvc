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
        $whereSql   = self::getWhereSql($where);
        $bindParams = self::getBindingParams($where);
        $orderSql   = self::getOrderBySql($orderBys);
        $data       = self::getPaginateData($whereSql, $tableName, $page, $perPage, $bindParams, $orderSql);
        $meta       = self::getPaginateMeta($whereSql, $tableName, $page, $perPage, $bindParams);
        return compact('data', 'meta');
    }

    private function fill(array $attributes): void
    {
        if (count($this->fillable) > 0) {
            $this->attributes = array_intersect_key($attributes, array_flip($this->fillable));
        }
    }


    private static function getWhereSql(array $where): string
    {
        $mappedWhere = [];
        foreach ($where as $attr => $value) {
            if (is_array($value)) {
                $questionMarks = implode(',', array_fill(0, count($value), '?'));
                $mappedWhere[] = "$attr IN ($questionMarks)";
            } else {
                $mappedWhere[] = "$attr = ?";
            }
        }
        return implode(" AND ", $mappedWhere);
    }

    private static function getBindingParams(array $where): array
    {
        $bindParams = [];
        foreach ($where as $value) {
            if (is_array($value)) {
                $bindParams = array_merge($bindParams, $value);
            } else {
                $bindParams[] = $value;
            }
        }
        return $bindParams;
    }

    private static function mapData(array $rows): array
    {
        $data = [];
        foreach ($rows as $row) {
            $instance = new static();
            foreach ($row as $name => $value) {
                $instance->{$name} = $value;
            }
            $data[] = $instance;
        }
        return $data;
    }

    private static function getOrderBySql(array $orderBys): string
    {
        if (!isset($orderBys[self::primaryKey()])) {
            $orderBys[self::primaryKey()] = "ASC";
        }

        $orderSqlArray = [];
        foreach ($orderBys as $attr => $dir) {
            $dir             = strtoupper($dir) === "DESC" ? "DESC" : "ASC";
            $orderSqlArray[] = "$attr $dir";
        }

        return implode(", ", $orderSqlArray);
    }

    private static function getPaginateMeta(
         string $whereSql,
         string $tableName,
         int $page,
         int $perPage,
         array $bindParams
    ): array {
        if ($whereSql) {
            $statement = self::prepare("SELECT count(*) as count FROM $tableName WHERE $whereSql");
        } else {
            $statement = self::prepare("SELECT count(*) as count FROM $tableName");
        }

        $statement->execute($bindParams);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $total = $statement->fetch()['count'];
        return [
             'page'      => $page,
             'per_page'  => $perPage,
             'total'     => $total,
             'last_page' => intdiv($total, $perPage) + ($total % $perPage),
        ];
    }

    private static function getPaginateData(
         string $whereSql,
         string $tableName,
         int $page,
         int $perPage,
         array $bindParams,
         string $orderSql
    ): array {
        $offset = (max($page, 0) - 1) * $perPage;
        if ($whereSql) {
            $queryString = "SELECT * FROM $tableName WHERE $whereSql ORDER BY $orderSql LIMIT $perPage OFFSET $offset";
        } else {
            $queryString = "SELECT * FROM $tableName ORDER BY $orderSql LIMIT $perPage OFFSET $offset";
        }
        $statement = self::prepare($queryString);
        $statement->execute($bindParams);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $statement->fetchAll();
        return self::mapData($rows);
    }
}
