<?php

use yii\db\Migration;

/**
 * Class m200105_205517_news2sitemap
 */
class m200105_205517_news2sitemap extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'in_sitemap', $this->boolean()->defaultValue(true)->after('source'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'in_sitemap');
    }
}
