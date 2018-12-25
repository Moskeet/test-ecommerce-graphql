<?php declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181225161438 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE basket_item (id INT AUTO_INCREMENT NOT NULL, basket_id INT NOT NULL, item_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_D4943C2B1BE1FB52 (basket_id), INDEX IDX_D4943C2B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, UNIQUE INDEX UNIQ_2246507B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2B126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket_item DROP FOREIGN KEY FK_D4943C2B1BE1FB52');
        $this->addSql('DROP TABLE basket_item');
        $this->addSql('DROP TABLE basket');
    }
}
