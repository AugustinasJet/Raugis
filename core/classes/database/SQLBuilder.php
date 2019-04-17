<?php

namespace Core\Database;

class SQLBuilder extends Core\Database\Abstracts\SQLBuilder {

    public static function bind($column): string {
        return ':' . str_replace(' ', '_', $column);
    }

    public static function binds($column_array): string {
        foreach ($column_array as $column) {
            $arr[] = self::bind($column);
        }
        
        return implode(', ', $column_array);
    }

    public static function column($column): string {
        return '`' . $column . '`';
    }

    public static function columnEqualBind($column): string {
        return self::column($column) . '=' . self::bind($column);
    }

    public static function columns($column_array): string {
        foreach ($column_array as $value) {
            $arr[] = '`' . $value . '`,';
        }
        
        return implode(', ', $arr);
    }

    public static function columnsEqualBinds($column_array, $delimiter = ', '): string {
        foreach ($column_array as $column) {
            $arr []= self::columnEqualBind($column) . $delimiter;
        }
        
        return implode($delimiter, $arr);
    }

    public static function value($value): string {
        return '\'' . $value . '\'';
    }

    public static function values($value_array): string {
        foreach ($value_array as $value) {
            $arr[] = self::value($value);
        }
        
        return implode(', ', $arr);
    }

}
