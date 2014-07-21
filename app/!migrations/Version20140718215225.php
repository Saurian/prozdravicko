<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140718215225 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) NOT NULL, page VARCHAR(64) DEFAULT NULL, section VARCHAR(64) NOT NULL, text LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX page_idx (page), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE catalog_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, url VARCHAR(64) NOT NULL, text LONGTEXT NOT NULL, image TINYTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_349BC7DFF47645AE (url), INDEX IDX_349BC7DF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE catalog_item (id INT AUTO_INCREMENT NOT NULL, catalog_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, link VARCHAR(255) NOT NULL, url VARCHAR(64) NOT NULL, text LONGTEXT NOT NULL, parameters LONGTEXT NOT NULL, content LONGTEXT NOT NULL, warning LONGTEXT NOT NULL, application LONGTEXT NOT NULL, sign LONGTEXT NOT NULL, image VARCHAR(64) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6AAE9568F47645AE (url), INDEX IDX_6AAE9568CC3C66FC (catalog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(32) NOT NULL, password VARCHAR(40) NOT NULL, name VARCHAR(64) NOT NULL, role VARCHAR(64) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE catalog_category ADD CONSTRAINT FK_349BC7DF727ACA70 FOREIGN KEY (parent_id) REFERENCES catalog_category (id)");
        $this->addSql("ALTER TABLE catalog_item ADD CONSTRAINT FK_6AAE9568CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog_category (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE catalog_category DROP FOREIGN KEY FK_349BC7DF727ACA70");
        $this->addSql("ALTER TABLE catalog_item DROP FOREIGN KEY FK_6AAE9568CC3C66FC");
        $this->addSql("DROP TABLE article");
        $this->addSql("DROP TABLE catalog_category");
        $this->addSql("DROP TABLE catalog_item");
        $this->addSql("DROP TABLE user");
    }
}
