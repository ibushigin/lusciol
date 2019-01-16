<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116172430 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8486F9AC FOREIGN KEY (adress_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_9474526C8486F9AC ON comment (adress_id)');
        $this->addSql('ALTER TABLE message ADD subject VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8486F9AC');
        $this->addSql('DROP INDEX IDX_9474526C8486F9AC ON comment');
        $this->addSql('ALTER TABLE message DROP subject');
    }
}
