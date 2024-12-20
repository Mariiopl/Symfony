<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211111502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pregunta (id INT AUTO_INCREMENT NOT NULL, enunciado VARCHAR(255) NOT NULL, opcion_a VARCHAR(255) NOT NULL, opcion_b VARCHAR(255) NOT NULL, opcion_c VARCHAR(255) DEFAULT NULL, opcion_d VARCHAR(255) DEFAULT NULL, opcion_correcta VARCHAR(1) NOT NULL, f_inicio DATETIME NOT NULL, f_fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE respuesta (id INT AUTO_INCREMENT NOT NULL, pregunta_id INT NOT NULL, marca_temporal DATETIME NOT NULL, respuesta_elegida VARCHAR(1) NOT NULL, INDEX IDX_6C6EC5EE31A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EE31A5801E FOREIGN KEY (pregunta_id) REFERENCES pregunta (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE respuesta DROP FOREIGN KEY FK_6C6EC5EE31A5801E');
        $this->addSql('DROP TABLE pregunta');
        $this->addSql('DROP TABLE respuesta');
    }
}
