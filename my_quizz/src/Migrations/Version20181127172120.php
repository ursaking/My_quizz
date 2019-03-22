<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181127172120 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question ADD quizz_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EBA934BCD ON question (quizz_id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7EA48A758');
        $this->addSql('DROP INDEX IDX_5FB6DEC7EA48A758 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP id_quizz_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBA934BCD');
        $this->addSql('DROP INDEX IDX_B6F7494EBA934BCD ON question');
        $this->addSql('ALTER TABLE question DROP quizz_id');
        $this->addSql('ALTER TABLE reponse ADD id_quizz_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7EA48A758 FOREIGN KEY (id_quizz_id) REFERENCES quizz (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7EA48A758 ON reponse (id_quizz_id)');
    }
}
