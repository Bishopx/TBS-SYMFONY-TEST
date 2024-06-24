<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217111203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $table = $schema->createTable('contact');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'primary' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('firstname', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime');

        $table = $schema->createTable('product');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'primary' => true]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime');

        $table = $schema->createTable('subscription');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'primary' => true]);
        $table->addColumn('contact_id', 'integer');
        $table->addColumn('product_id', 'integer');
        $table->addColumn('begin_date', 'datetime');
        $table->addColumn('end_date', 'datetime');
        $table->addColumn('created_at', 'datetime');
    
        $table->addForeignKeyConstraint('FK_subscription_contact', ['contact_id'], ['contact'], ['id']);
        $table->addForeignKeyConstraint('FK_subscription_product', ['product_id'], ['product'], ['id']);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('contact');
        $schema->dropTable('product');
        $schema->dropTable('subscription');

    }
}
