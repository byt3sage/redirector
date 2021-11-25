<?php
/**
 * Redirector plugin for Craft CMS 3.x
 *
 * A simple way to add 301/302 redirects within CraftCMS
 *
 * @link      https://github.com/jaetooledev
 * @copyright Copyright (c) 2021 Jae Toole
 */

namespace jaetooledev\redirector\migrations;

use jaetooledev\redirector\Redirector;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Redirector Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Jae Toole
 * @package   Redirector
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // redirector_redirects table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%redirector_redirects}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%redirector_redirects}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                    'siteId' => $this->integer()->notNull(),
                    'fromUrl' => $this->string(255)->notNull()->defaultValue(''),
                    'toUrl' => $this->string(255)->notNull()->defaultValue(''),
                    'type' => $this->integer(3)->notNull()->defaultValue(301)
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
    // redirector_redirects table
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%redirector_redirects}}', 'siteId'),
            '{{%redirector_redirects}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // redirector_redirects table
        $this->dropTableIfExists('{{%redirector_redirects}}');
    }
}
