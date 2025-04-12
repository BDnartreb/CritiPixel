<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330174639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE "tag_id_seq" INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE video_game_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE review (id INT NOT NULL, video_game_id INT NOT NULL, user_id INT NOT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C616230A8 ON review (video_game_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "tag" (id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_389B78377153098 ON "tag" (code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(60) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE video_game (id INT NOT NULL, title VARCHAR(100) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, release_date DATE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, test TEXT DEFAULT NULL, rating INT DEFAULT NULL, average_rating INT DEFAULT NULL, number_of_ratings_per_value_number_of_one INT NOT NULL, number_of_ratings_per_value_number_of_two INT NOT NULL, number_of_ratings_per_value_number_of_three INT NOT NULL, number_of_ratings_per_value_number_of_four INT NOT NULL, number_of_ratings_per_value_number_of_five INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_24BC6C50989D9B62 ON video_game (slug)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN video_game.release_date IS '(DC2Type:date_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN video_game.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE video_game_tags (video_game_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(video_game_id, tag_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46D6859F16230A8 ON video_game_tags (video_game_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46D6859FBAD26311 ON video_game_tags (tag_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C616230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video_game_tags ADD CONSTRAINT FK_46D6859F16230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video_game_tags ADD CONSTRAINT FK_46D6859FBAD26311 FOREIGN KEY (tag_id) REFERENCES "tag" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE review_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE "tag_id_seq" CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE "user_id_seq" CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE video_game_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C616230A8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video_game_tags DROP CONSTRAINT FK_46D6859F16230A8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video_game_tags DROP CONSTRAINT FK_46D6859FBAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE review
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "tag"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE video_game
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE video_game_tags
        SQL);
    }
}
