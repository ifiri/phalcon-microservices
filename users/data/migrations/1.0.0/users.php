<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersMigration_100
 */
class UsersMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('users', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 10,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'primary' => true,
                    ]
                ),
                new Column(
                    'login',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 100,
                        'notNull' => true,
                    ]
                ),
                new Column(
                    'password',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 100,
                        'notNull' => true,
                    ]
                ),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'notNull' => true,
                    ]
                ),
                new Column(
                    'created_at',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'default' => time(),
                    ]
                ),
            ],
            'indexes' => [
                new Index('login_UNIQUE', ['login'], 'UNIQUE'),
                new Index('name_SIMPLE', ['name'])
            ], 
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'TABLE_COLLATION' => 'utf8mb4_unicode_ci'
            ],
        ]);
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this->getConnection()->insert(
            'users',
            [
                'id' => 1,
                'login' => 'admin',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'name' => 'John Wayne',
                'created_at' => time(),
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $this->getConnection()->delete(
            'users',
            'id=1'
        );
    }
}
