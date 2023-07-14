<?php

namespace App\Models;

use App\Contracts\IModel;
use App\Helpers\Inflector;

/**
 * @property string $table   The model table name if differs from class plural name (static)
 * @property array  $fields  The accessible model fields (static)
 * @property int    $id      The model unique key
 */
abstract class Model implements IModel
{
    /**
     * Find and return instance of the model by id
     *
     * @param int $id
     * @return object|null
     */
    public static function find(int $id): ?object
    {
        if ($record = data_get(static::getTableData(), $id)) {
            return static::cast($record);
        }

        return null;
    }

    /**
     * Return all model records
     *
     * @return array
     */
    public static function all(): array
    {
        return array_map(function ($record) {
            return static::cast($record);
        }, static::getTableData());
    }

    /**
     * Returns all model records by that met criteria
     *
     * @param string $field
     * @param string $operator
     * @param [type] $value
     * @return IModel|null
     */
    public static function where(string $field, string $operator, $value): array
    {
        return array_filter(static::getTableData(), function ($record) use ($field, $operator, $value) {
            if (
                property_exists($record, $field) &&
                static::criteriaMet($record->$field, $operator, $value)
            ) return $record;
        });
    }

    /**
     * Returns first model record by that met criteria
     *
     * @param string $field
     * @param string $operator
     * @param [type] $value
     * @return IModel|null
     */
    public static function first(string $field, string $operator, $value): ?IModel
    {
        $result = array_first(static::getTableData(), function ($record) use ($field, $operator, $value) {
            if (
                property_exists($record, $field) &&
                static::criteriaMet($record->$field, $operator, $value)
            ) return $record;
        });

        return $result ? static::cast($result) : null;
    }

    /**
     * Saves the model record
     *
     * @return bool
     */
    public function save(): bool
    {
        $records   = static::getTableData();
        $lastId    = count($records) ? max(array_keys($records)) : 0;
        $recordId  = isset($this->id) ? $this->id : ++$lastId;
        $tablePath = static::getTablePath();

        // Remove properties out of accessible $fields
        foreach (get_object_vars($this) as $key => $value) {
            if (isset(static::$fields) && array_search($key, static::$fields) === false) {
                unset($this->$key);
            }
        }

        // Fill accessible $fields
        foreach (static::$fields as $key) {
            data_fill($this, $key, data_get($this, $key));
        }

        // Set object id
        $this->id = $recordId;

        $records[$recordId] = (object) get_object_vars($this);
        $records            = json_encode($records);

        return file_put_contents($tablePath, $records) !== false;
    }

    /**
     * Deletes the model record
     *
     * @return bool
     */
    public function delete(): bool
    {
        $records   = static::getTableData();
        $tablePath = static::getTablePath();

        if (array_key_exists($this->id, $records)) {
            array_forget($records, $this->id);
            
            return file_put_contents($tablePath, json_encode($records)) !== false;
        }

        return false;
    }

    /**
     * Gets the path to the model data
     *
     * @return string
     */
    private function getTablePath(): string
    {
        $tableName = property_exists(get_called_class(), 'table')
            ? static::$table
            : strtolower(Inflector::pluralize((new \ReflectionClass(get_called_class()))->getShortName()));

        $tablePath = database_path() . $tableName . ".json";

        if (!file_exists($tablePath)) {
            touch($tablePath);
        };

        return $tablePath;
    }

    /**
     * Gets the model data from file
     *
     * @return array
     */
    private function getTableData(): array
    {
        $tablePath = static::getTablePath();

        $data = to_array(file_get_contents($tablePath));

        return array_combine(array_column($data, 'id'), $data);
    }

    /**
     * Cast object to called class
     *
     * @param object $instance  The object to be casted
     * @return IModel
     */
    private function cast(object $instance): IModel
    {
        $accessibleFields = isset(static::$fields)
            ? array_prepend(static::$fields, 'id')
            : null;

        foreach (get_object_vars($instance) as $key => $value) {
            if (!empty($accessibleFields) && array_search($key, $accessibleFields) === false) {
                unset($instance->$key);
            }
        }

        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen(get_called_class()),
            get_called_class(),
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }

    /**
     * Criteria checker
     *
     * @param string $value1 - the value to be compared
     * @param string $operator - the operator
     * @param string $value2 - the value to test against
     * @return boolean - criteria met/not met
     */
    protected function criteriaMet($value1, $operator, $value2)
    {
        switch ($operator) {
            case '<':
                return $value1 < $value2;
            case '<=':
                return $value1 <= $value2;
            case '>':
                return $value1 > $value2;
            case '>=':
                return $value1 >= $value2;
            case '==':
                return $value1 == $value2;
            case '!=':
                return $value1 != $value2;
            case 'like':
                return stripos($value1, $value2) !== false;
            default:
                return false;
        }
    }
}
