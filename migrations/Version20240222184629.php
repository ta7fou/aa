<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222184629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A55EB747A3');
        $this->addSql('DROP INDEX IDX_2694D7A55EB747A3 ON demande');
        $this->addSql('ALTER TABLE demande CHANGE id_user id_user INT NOT NULL, CHANGE animal_id_id animal_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A58E962C16 FOREIGN KEY (animal_id) REFERENCES animals (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A58E962C16 ON demande (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A58E962C16');
        $this->addSql('DROP INDEX IDX_2694D7A58E962C16 ON demande');
        $this->addSql('ALTER TABLE demande CHANGE id_user id_user INT DEFAULT NULL, CHANGE animal_id animal_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A55EB747A3 FOREIGN KEY (animal_id_id) REFERENCES animals (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A55EB747A3 ON demande (animal_id_id)');
    }
}
