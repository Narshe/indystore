<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191016143853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD product_detail_id INT NOT NULL, DROP detail_id, DROP stock');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB670B536 FOREIGN KEY (product_detail_id) REFERENCES product_detail (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADB670B536 ON product (product_detail_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB670B536');
        $this->addSql('DROP INDEX UNIQ_D34A04ADB670B536 ON product');
        $this->addSql('ALTER TABLE product ADD stock INT NOT NULL, CHANGE product_detail_id detail_id INT NOT NULL');
    }
}
