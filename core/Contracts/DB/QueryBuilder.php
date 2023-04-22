<?php

namespace Core\Contracts\DB;

interface QueryBuilder
{
    public function table(string $table): static;

    public function select(array|string $columns = '*'): static;

    public function where(string $column, string $operator, mixed $value = null): static;

    public function whereIn(string $column, array $values): static;

    public function whereNull(string $column): static;

    public function whereNotNull(string $column): static;

    public function orderBy(string $column, string $direction = 'ASC'): static;

    public function limit(int $limit): static;

    public function offset(int $offset): static;

    public function get(): array|false;

    public function first(): mixed;

    public function insert(array $data): bool;

    public function delete(): bool;

    public function update(array $data): bool;

    public function count(): int;
}
