<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120131321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctor_configuration (id INT AUTO_INCREMENT NOT NULL, medecin_id INT NOT NULL, limit_patient_number INT NOT NULL, time_to_start TIME NOT NULL, current_patient_number INT NOT NULL, last_consultation DATETIME NOT NULL, UNIQUE INDEX UNIQ_9EA66D0B4F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE doctor_configuration ADD CONSTRAINT FK_9EA66D0B4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor_configuration DROP FOREIGN KEY FK_9EA66D0B4F31A84');
        $this->addSql('DROP TABLE doctor_configuration');
    }
}
