<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307102447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD9B6B5FBA FOREIGN KEY (account_id) REFERENCES compte_client (id)');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE9327612E2');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE986A5793C');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9327612E2 FOREIGN KEY (destination_c_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE986A5793C FOREIGN KEY (compte_id_id) REFERENCES compte_client (id)');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652609D86650F');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652609D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D629D86650F');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D62650E944D');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D62892CBB0E');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D629D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D62650E944D FOREIGN KEY (nom_pack) REFERENCES pack (nom_pack)');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D62892CBB0E FOREIGN KEY (type_name) REFERENCES type (nom_type)');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEBCF5E72D');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEF2C56620');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_credit (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEF2C56620 FOREIGN KEY (compte_id) REFERENCES compte_client (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamtion DROP FOREIGN KEY FK_5C8EEBA167983896');
        $this->addSql('ALTER TABLE reclamtion ADD CONSTRAINT FK_5C8EEBA167983896 FOREIGN KEY (cheque_id_id) REFERENCES cheque (id)');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFCE062FF9');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFCE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11AF787D1');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11AF787D1 FOREIGN KEY (id_c_id) REFERENCES carte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD9B6B5FBA');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE9327612E2');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE986A5793C');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9327612E2 FOREIGN KEY (destination_c_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE986A5793C FOREIGN KEY (compte_id_id) REFERENCES compte_client (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652609D86650F');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652609D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D62892CBB0E');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D62650E944D');
        $this->addSql('ALTER TABLE compte_client DROP FOREIGN KEY FK_1DDD5D629D86650F');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D62892CBB0E FOREIGN KEY (type_name) REFERENCES type (nom_type) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D62650E944D FOREIGN KEY (nom_pack) REFERENCES pack (nom_pack) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_client ADD CONSTRAINT FK_1DDD5D629D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEF2C56620');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEBCF5E72D');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEF2C56620 FOREIGN KEY (compte_id) REFERENCES compte_client (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_credit (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('ALTER TABLE reclamtion DROP FOREIGN KEY FK_5C8EEBA167983896');
        $this->addSql('ALTER TABLE reclamtion ADD CONSTRAINT FK_5C8EEBA167983896 FOREIGN KEY (cheque_id_id) REFERENCES cheque (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFCE062FF9');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFCE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11AF787D1');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11AF787D1 FOREIGN KEY (id_c_id) REFERENCES carte (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
