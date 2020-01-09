<?php

use yii\db\Migration;

/**
 * Class m200109_141741_news2amp
 */
class m200109_141741_news2amp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'in_amp', $this->boolean()->defaultValue(true));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'in_amp');
    }
}
