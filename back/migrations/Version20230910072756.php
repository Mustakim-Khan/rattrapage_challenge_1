<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910072756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tasks_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, tasks_list_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, desciption VARCHAR(255) NOT NULL, priority INT NOT NULL, end_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB256DE36C0D ON task (tasks_list_id)');
        $this->addSql('CREATE INDEX IDX_527EDB257E3C61F9 ON task (owner_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25B03A8386 ON task (created_by_id)');
        $this->addSql('CREATE TABLE tasks_list (id INT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AE27566E2B36786B ON tasks_list (title)');
        $this->addSql('CREATE INDEX IDX_AE27566E7E3C61F9 ON tasks_list (owner_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256DE36C0D FOREIGN KEY (tasks_list_id) REFERENCES tasks_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tasks_list ADD CONSTRAINT FK_AE27566E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tasks_list_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB256DE36C0D');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB257E3C61F9');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE tasks_list DROP CONSTRAINT FK_AE27566E7E3C61F9');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE tasks_list');
        $this->addSql('DROP TABLE "user"');
    }
}
