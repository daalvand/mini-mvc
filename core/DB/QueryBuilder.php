<?php

namespace Core\DB;

use Core\Contracts\DB\Database;
use Core\Contracts\DB\Model;
use Core\Contracts\DB\QueryBuilder as QueryBuilderContract;
use PDO;

class QueryBuilder implements QueryBuilderContract
{
    protected array    $params;
    protected array    $values;
    protected array    $wheres;
    protected string   $select;
    protected array    $orderBy;
    protected int|null $limit;
    protected int|null $offset;
    protected string   $sql;
    protected string   $model;
    protected string   $table;

    public function __construct(protected Database $database)
    {
        $this->reset();
    }

    public function model(string $modelClass): static
    {
        /** @var Model $modelClass */
        $this->table = $modelClass::tableName();
        $this->model = $modelClass;
        return $this;
    }

    public function select(array|string $columns = '*'): static
    {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }
        $this->select = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value = null): static
    {
        if (func_num_args() === 2) {
            $value    = $operator;
            $operator = '=';
        }
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $this->wheres[] = [$column, 'IN', $values];
        return $this;
    }

    public function whereNull(string $column): static
    {
        $this->wheres[] = [$column, 'IS', null];
        return $this;
    }

    public function whereNotNull(string $column): static
    {
        $this->wheres[] = [$column, 'IS NOT', null];
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = [$column, $direction];
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(bool $raw = false): array
    {
        $this->parseWheres();

        $sql = "SELECT $this->select FROM $this->table $this->sql";

        if (!empty($this->orderBy)) {
            [$column, $direction] = $this->orderBy;
            $sql .= " ORDER BY $column $direction";
        }

        if (!is_null($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }

        if (!is_null($this->offset)) {
            $sql .= " OFFSET $this->offset";
        }

        $stmt = $this->database->pdo()->prepare($sql);
        $stmt->execute(array_merge($this->values, $this->params));
        $this->reset();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            return [];
        }
        if ($raw) {
            return $result;
        }
        return $this->convertRawToModel($result);
    }

    protected function convertRawToModel(array $result): array
    {
        $instances = [];
        foreach ($result as $row) {
            $instance = new $this->model;
            foreach ($row as $name => $value) {
                $instance->{$name} = $value;
            }
            $instances[] = $instance;
        }
        return $instances;
    }


    public function insert(array $data): bool
    {
        $columns      = array_keys($data);
        $values       = array_values($data);
        $placeholders = rtrim(str_repeat('?,', count($values)), ',');
        $sql          = "INSERT INTO $this->table (" . implode(',', $columns) . ") VALUES ($placeholders)";
        $stmt         = $this->database->pdo()->prepare($sql);
        $stmt->execute($values);
        $this->reset();
        return $stmt->rowCount() > 0;
    }

    public function delete(): bool
    {
        $this->sql = "DELETE FROM $this->table";
        if (!empty($this->wheres)) {
            $this->parseWheres();
        }
        $stmt = $this->database->pdo()->prepare($this->sql);
        $stmt->execute(array_merge($this->values, $this->params));
        $this->reset();
        return $stmt->rowCount() > 0;
    }

    public function update(array $data): bool
    {
        $columns = array_keys($data);
        $values  = array_values($data);

        $this->sql = "UPDATE $this->table SET ";

        foreach ($columns as $column) {
            $this->sql .= "$column=?, ";
        }

        $this->sql = rtrim($this->sql, ', ');

        if (!empty($this->wheres)) {
            $this->parseWheres();
        }

        $stmt = $this->database->pdo()->prepare($this->sql);
        $stmt->execute(array_merge($values, $this->values, $this->params));
        $this->reset();

        return $stmt->rowCount() > 0;
    }

    public function count(): int
    {
        $this->select = 'COUNT(*) as count';
        $result       = $this->get(true);
        return $result[0]['count'] ?? 0;
    }

    private function parseWheres(): void
    {
        if ($this->wheres) {
            $this->sql .= " WHERE ";

            foreach ($this->wheres as $where) {
                [$column, $operator, $value] = $where;
                if (is_array($value)) {
                    $placeholders = rtrim(str_repeat('?,', count($value)), ',');
                    $this->sql    .= "$column $operator ($placeholders) AND ";
                    $this->values = array_merge($this->values, $value);
                } else {
                    $this->sql      .= "$column $operator ? AND ";
                    $this->values[] = $value;
                }
            }

            $this->sql = rtrim($this->sql, ' AND');
        }
    }

    protected function reset(): void
    {
        $this->table   = '';
        $this->params  = [];
        $this->values  = [];
        $this->wheres  = [];
        $this->select  = '*';
        $this->orderBy = [];
        $this->limit   = null;
        $this->offset  = null;
        $this->sql     = '';
    }
}
