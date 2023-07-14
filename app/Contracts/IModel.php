<?php

namespace App\Contracts;

interface IModel
{
    public static function find(int $id): ?object;

    public static function where(string $field, string $operator, $value): array;

    public static function first(string $field, string $operator, $value): ?IModel;

    public static function all(): array;

    public function save(): bool;

    public function delete(): bool;
}
