<?php

namespace Core\DB;

class ModelQueryBuilder extends QueryBuilder
{
    protected string $model;

    public function model(string $modelClass): static
    {
        /** @var Model $modelClass */
        $this->table = $modelClass::tableName();
        $this->model = $modelClass;
        return $this;
    }

    public function get(): array
    {
        $result = parent::get();
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
}
