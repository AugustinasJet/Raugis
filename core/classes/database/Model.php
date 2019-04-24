<?php

namespace Core\Database;

class Model extends Core\Database\Abstracts\Model {

    public function create() {
        $table = [
            [
                'name' => 'my_column',
                'type' => Model::TEXT_SHORT,
                'flags' => [Model::FLAG_NOT_NULL]
            ],
            [
                'name' => 'my_other_column',
                'type' => Model::NUMBER_SHORT,
                'flags' => [FLAG_NOT_NULL, FLAG_AUTO_INCREMENT]
            ],
        ];
        foreach ($this->fields as $field) {
            $SQL_columns = [];

            foreach ($this->fields as $field) {
                $SQL_columns[] = strtr('@col_name @col_type @col_flags', [
                    '@col_name' => SQLBuilder::column($field['name']),
                    '@col_type' => $field['type'],
                    '@col_flags' => isset($field['flags']) ? implode(' ', $field['flags']) : ''
                ]);
            }

            $sql = strtr('CREATE TABLE @table_name (@columns);', [
                '@table_name' => SQLBuilder::table($this->table_name),
                '@columns' => implode(', ', $SQL_columns)
            ]);

            return $this->pdo->exec($sql);
        }
    }

    public function insert($row) {

        $columns = array_keys($row);

        $sql = strtr('INSERT INTO @table (@columns) VALUES (@values)', [
            '@table' => SQLBuilder::table($this->table_name),
            '@columns' => SQLBuilder::columns($columns),
            '@values' => SQLBuilder::binds($columns)
        ]);

        $query = $this->pdo->prepare($sql);

        foreach ($row as $column => $value)
            $query->bindValue(SQLBuilder::bind($column), $value);

        try {
            $query->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Nepavyko insertinti eilutes');
        }
    }

    public function insertIfNotExists($row, $unique_columns) {
        if (!$this->load($unique_columns)) {
            return $this->insert($row);
        }
    }

    public function update($row = array(), $conditions = array()) {
        $row_keys = array_keys($row);
        $conditions_keys = array_keys($conditions);

        if ($conditions) {
            $sql = strtr('UPDATE @table SET @values WHERE @condition', [
                '@table' => SQLBuilder::table($this->table_name),
                '@values' => SQLBuilder::columnsEqualBinds($row_keys),
                '@condition' => SQLBuilder::columnsEqualBinds($conditions_keys, ' AND ', 'cond_')
            ]);
        } else {
            $sql = strtr('UPDATE @table SET @values', [
                '@table' => SQLBuilder::table($this->table_name),
                '@values' => SQLBuilder::columnsEqualBinds($row_keys)
            ]);
        }


        $query = $this->pdo->prepare($sql);

        foreach ($row as $column => $value) {
            $query->bindValue(SQLBuilder::bind($column), $value);
        }
        foreach ($conditions as $column => $value) {
            $query->bindValue(SQLBuilder::bind($column, 'cond_'), $value);
        }

        try {
            return $query->execute();
        } catch (PDOException $ex) {
            throw new Exception('Database error: Unable to update table ' . $ex->getMessage());
        }
    }

}
