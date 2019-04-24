<?php

namespace App\Model;

class Users extends \Core\Database\Model {

    public function __construct(\Core\Database\Connection $conn) {
        parent::__construct($conn, 'users', [
            ['name' => 'email',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL, self::FLAG_PRIMARY]
            ],
            [
                'name' => 'password',
                'type' => self::NUMBER_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            ['name' => 'full_name',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            ['name' => 'age',
                'type' => self::NUMBER_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            ['name' => 'gender',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            ['name' => 'photo',
                'type' => self::TEXT_MED,
                'flags' => [self::FLAG_NOT_NULL]
            ],
            ['name' => 'created_at',
                'type' => self::DATETIME,
                'flags' => [self::DATETIME_AUTO_ON_CREATE]
            ],
            ['name' => 'updated_at',
                'type' => self::TIMESTAMP,
                'flags' => [self::TIMESTAMP_AUTO_ON_UPDATE]
            ],
        ]);
    }

}