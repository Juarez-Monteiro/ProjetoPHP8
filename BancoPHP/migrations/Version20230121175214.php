<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230121175214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipo_conta ADD tipo_conta LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP conta_corrente, DROP conta_poupanca');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tipo_conta ADD conta_corrente VARCHAR(2) DEFAULT NULL, ADD conta_poupanca VARCHAR(2) DEFAULT NULL, DROP tipo_conta');
    }
}
