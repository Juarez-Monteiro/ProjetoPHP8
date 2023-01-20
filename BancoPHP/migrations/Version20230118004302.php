<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118004302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agencia (id INT AUTO_INCREMENT NOT NULL, gerente_id INT DEFAULT NULL, nome_agencia VARCHAR(255) NOT NULL, codigo VARCHAR(255) NOT NULL, endereco VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EB6C2B995AEA750D (gerente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conta (id INT AUTO_INCREMENT NOT NULL, agencia_id INT DEFAULT NULL, user_id INT DEFAULT NULL, tipos_id INT DEFAULT NULL, saldo VARCHAR(255) NOT NULL, numero_da_conta INT NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_485A16C3A6F796BE (agencia_id), INDEX IDX_485A16C3A76ED395 (user_id), INDEX IDX_485A16C3A3DCB738 (tipos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gerente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_conta (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transacao (id INT AUTO_INCREMENT NOT NULL, conta_destino_id INT DEFAULT NULL, conta_origem_id INT DEFAULT NULL, descricao VARCHAR(255) NOT NULL, valor DOUBLE PRECISION NOT NULL, INDEX IDX_6C9E60CE88825F03 (conta_destino_id), INDEX IDX_6C9E60CE332BCA77 (conta_origem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agencia ADD CONSTRAINT FK_EB6C2B995AEA750D FOREIGN KEY (gerente_id) REFERENCES gerente (id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3A6F796BE FOREIGN KEY (agencia_id) REFERENCES agencia (id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3A3DCB738 FOREIGN KEY (tipos_id) REFERENCES tipo_conta (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE88825F03 FOREIGN KEY (conta_destino_id) REFERENCES conta (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE332BCA77 FOREIGN KEY (conta_origem_id) REFERENCES conta (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia DROP FOREIGN KEY FK_EB6C2B995AEA750D');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3A6F796BE');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3A76ED395');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3A3DCB738');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CE88825F03');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CE332BCA77');
        $this->addSql('DROP TABLE agencia');
        $this->addSql('DROP TABLE conta');
        $this->addSql('DROP TABLE gerente');
        $this->addSql('DROP TABLE tipo_conta');
        $this->addSql('DROP TABLE transacao');
        $this->addSql('DROP TABLE user');
    }
}
