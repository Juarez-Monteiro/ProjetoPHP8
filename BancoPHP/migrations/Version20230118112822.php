<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118112822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gerente ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gerente ADD CONSTRAINT FK_306C486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_306C486DA76ED395 ON gerente (user_id)');
        $this->addSql('ALTER TABLE user ADD nome_cliente VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cliente');
        $this->addSql('ALTER TABLE gerente DROP FOREIGN KEY FK_306C486DA76ED395');
        $this->addSql('DROP INDEX UNIQ_306C486DA76ED395 ON gerente');
        $this->addSql('ALTER TABLE gerente DROP user_id');
        $this->addSql('ALTER TABLE user DROP nome_cliente');
    }
}
