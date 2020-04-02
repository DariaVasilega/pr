<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312205742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, avatar_id INT DEFAULT NULL, roles JSON NOT NULL, token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, login VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(20) NOT NULL, slug VARCHAR(255) NOT NULL, verification TINYINT(1) NOT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), UNIQUE INDEX UNIQ_8D93D6496BF700BD (status_id), UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(20) NOT NULL, INDEX IDX_6DC044C57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A4C98D39FE54D947 (group_id), INDEX IDX_A4C98D39A76ED395 (user_id), PRIMARY KEY(group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_user (media_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4ED4099AEA9FDD75 (media_id), INDEX IDX_4ED4099AA76ED395 (user_id), PRIMARY KEY(media_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `post` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `friend` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, friend_id INT NOT NULL, created DATETIME NOT NULL, INDEX IDX_55EEAC61A76ED395 (user_id), INDEX IDX_55EEAC616A5458E8 (friend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `status` (id INT AUTO_INCREMENT NOT NULL, quote VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496BF700BD FOREIGN KEY (status_id) REFERENCES `status` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C57E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_user ADD CONSTRAINT FK_4ED4099AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_user ADD CONSTRAINT FK_4ED4099AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `post` ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `friend` ADD CONSTRAINT FK_55EEAC61A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `friend` ADD CONSTRAINT FK_55EEAC616A5458E8 FOREIGN KEY (friend_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C57E3C61F9');
        $this->addSql('ALTER TABLE group_user DROP FOREIGN KEY FK_A4C98D39A76ED395');
        $this->addSql('ALTER TABLE media_user DROP FOREIGN KEY FK_4ED4099AA76ED395');
        $this->addSql('ALTER TABLE `post` DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE `friend` DROP FOREIGN KEY FK_55EEAC61A76ED395');
        $this->addSql('ALTER TABLE `friend` DROP FOREIGN KEY FK_55EEAC616A5458E8');
        $this->addSql('ALTER TABLE group_user DROP FOREIGN KEY FK_A4C98D39FE54D947');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE media_user DROP FOREIGN KEY FK_4ED4099AEA9FDD75');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496BF700BD');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_user');
        $this->addSql('DROP TABLE `post`');
        $this->addSql('DROP TABLE `friend`');
        $this->addSql('DROP TABLE `status`');
    }
}
