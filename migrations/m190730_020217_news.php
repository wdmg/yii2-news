<?php

use yii\db\Migration;

/**
 * Class m190730_020217_news
 */
class m190730_020217_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news}}', [

            'id' => $this->bigPrimaryKey(),
            'name' => $this->string(128)->notNull(),
            'alias' => $this->string(128)->notNull(),

            'image' => $this->string(255)->null(),

            'excerpt' => $this->string(255)->null(),
            'content' => $this->text()->null(),

            'title' => $this->string(255)->null(),
            'description' => $this->string(255)->null(),
            'keywords' => $this->string(255)->null(),

            'status' => $this->tinyInteger(1)->null()->defaultValue(0),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(11)->null(),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(11)->null(),


        ], $tableOptions);

        $this->createIndex('{{%idx-news-alias}}', '{{%news}}', ['name', 'alias']);
        $this->createIndex('{{%idx-news-status}}', '{{%news}}', ['alias', 'status']);

        if ($this->db->driverName === 'mysql')
            $this->createIndex('{{%idx-news-content}}','{{%news}}', ['name', 'excerpt', 'content(250)'],false);
        else
            $this->createIndex('{{%idx-news-content}}','{{%news}}', ['name', 'excerpt', 'content'],false);

        $this->createIndex('{{%idx-news-author}}','{{%news}}', ['created_by', 'updated_by']);

        // If exist module `Users` set foreign key `created_by`, `updated_by` to `users.id`
        if (class_exists('\wdmg\users\models\Users')) {
            $userTable = \wdmg\users\models\Users::tableName();
	        $this->addForeignKey(
		        'fk_news_to_users1',
		        '{{%news}}',
		        'created_by',
		        $userTable,
		        'id',
		        'NO ACTION',
		        'CASCADE'
	        );
	        $this->addForeignKey(
		        'fk_news_to_users2',
		        '{{%news}}',
		        'updated_by',
		        $userTable,
		        'id',
		        'NO ACTION',
		        'CASCADE'
	        );
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-news-alias}}', '{{%news}}');
        $this->dropIndex('{{%idx-news-status}}', '{{%news}}');
        $this->dropIndex('{{%idx-news-content}}', '{{%news}}');
        $this->dropIndex('{{%idx-news-author}}', '{{%news}}');

        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $userTable = \wdmg\users\models\Users::tableName();
            if (!(Yii::$app->db->getTableSchema($userTable, true) === null)) {
	            $this->dropForeignKey(
		            'fk_news_to_users1',
		            '{{%news}}'
	            );
	            $this->dropForeignKey(
		            'fk_news_to_users2',
		            '{{%news}}'
	            );
            }
        }

        $this->truncateTable('{{%news}}');
        $this->dropTable('{{%news}}');
    }

}
