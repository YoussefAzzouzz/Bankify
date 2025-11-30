<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use App\Entity\Assurance;
use App\Entity\Carte;
use App\Entity\CategorieAssurance;
use App\Entity\CategorieCredit;
use App\Entity\Cheque;
use App\Entity\Compte;
use App\Entity\CompteClient;
use App\Entity\Credit;
use App\Entity\Image;
use App\Entity\Pack;
use App\Entity\Reclamtion;
use App\Entity\Remboursement;
use App\Entity\Transaction;
use App\Entity\Type;
use App\Entity\TypeC;
use App\Entity\User;
use App\Entity\Virement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Users (1 Admin + 3 Users)
        $admin = new User();
        $admin->setEmail('admin@bankify.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setNom('Admin');
        $admin->setPrenom('System');
        $admin->setIsActive(true);
        $admin->setIsVerified(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user$i@bankify.com");
            $user->setRoles(['ROLE_USER']);
            $user->setNom("Nom$i");
            $user->setPrenom("Prenom$i");
            $user->setIsActive(true);
            $user->setIsVerified(true);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
            $manager->persist($user);
            $users[] = $user;

            // Image for User
            $image = new Image();
            $image->setFilename("avatar$i.png");
            $image->setUser($user);
            $manager->persist($image);
        }

        // 2. Agence
        $agences = [];
        for ($i = 1; $i <= 3; $i++) {
            $agence = new Agence();
            $agence->setNomAgence("Agence $i");
            $agence->setEmailAgence("agence$i@bankify.com");
            $agence->setTelAgence("7100000$i");
            $manager->persist($agence);
            $agences[] = $agence;
        }

        // 3. CategorieAssurance
        $catAssurances = [];
        for ($i = 1; $i <= 3; $i++) {
            $cat = new CategorieAssurance();
            $cat->setNomCategorie("CatAssurance $i");
            $cat->setDescription("Description Assurance $i");
            $cat->setTypeCouverture("Type $i");
            $cat->setAgenceResponsable("Agence Resp $i");
            $manager->persist($cat);
            $catAssurances[] = $cat;
        }

        // 4. Assurance
        for ($i = 1; $i <= 3; $i++) {
            $assurance = new Assurance();
            $assurance->setTypeAssurance("TypeAssurance $i");
            $assurance->setMontantPrime(100.0 * $i);
            $assurance->setNomAssure("Assure $i");
            $assurance->setNomBeneficiaire("Beneficiaire $i");
            $assurance->setInfoAssurance("Info $i");
            $assurance->setAgence($agences[$i - 1]);
            $manager->persist($assurance);
        }

        // 5. Type (Account Type)
        $types = [];
        for ($i = 1; $i <= 3; $i++) {
            $type = new Type();
            $type->setNomType("TypeCompte$i"); // Alpha only
            $type->setDescription("Description Type $i");
            $manager->persist($type);
            $types[] = $type;
        }

        // 6. Pack
        $packs = [];
        for ($i = 1; $i <= 3; $i++) {
            $pack = new Pack();
            $pack->setNomPack("Pack$i"); // Alpha only? Check entity. Entity says alpha.
            // Pack entity: #[Assert\Type(type: 'alpha', message: 'Le nom du pack doit être alphabétique')]
            // "PackOne", "PackTwo", "PackThree" might be safer if numbers are disallowed.
            $packNames = ['PackOne', 'PackTwo', 'PackThree'];
            $pack->setNomPack($packNames[$i-1]);
            $pack->setDescription("Description Pack $i");
            $pack->setPrice((string)(10 * $i));
            $pack->setBenefits("Benefits $i");
            $pack->setId($i);
            $manager->persist($pack);
            $packs[] = $pack;
        }

        // 7. CompteClient & Compte
        $comptesClients = [];
        for ($i = 1; $i <= 3; $i++) {
            // CompteClient
            $cc = new CompteClient();
            $cc->setNom("Nom$i");
            $cc->setPrenom("Prenom$i");
            $cc->setTel("2000000$i");
            $cc->setMail("client$i@bankify.com");
            $cc->setRib(str_pad((string)$i, 16, '0', STR_PAD_LEFT));
            $cc->setSolde(1000.0 * $i);
            $cc->setUserID($users[$i - 1]);
            $cc->setNomType($types[$i - 1]);
            $cc->setNomPack($packs[$i - 1]);
            $manager->persist($cc);
            $comptesClients[] = $cc;

            // Compte (Generic)
            $c = new Compte();
            $c->setSolde(500.0 * $i);
            $c->setStatut("Actif");
            $c->setUserID($users[$i - 1]);
            $manager->persist($c);
        }

        // 8. TypeC (Card Type)
        $typeCs = [];
        $cardTypes = ['visa', 'mastercard', 'visa'];
        for ($i = 1; $i <= 3; $i++) {
            $tc = new TypeC();
            $tc->setType($cardTypes[$i-1]);
            $tc->setMontantMin(100);
            $tc->setMontantMax(5000);
            $manager->persist($tc);
            $typeCs[] = $tc;
        }

        // 9. Carte
        $cartes = [];
        for ($i = 1; $i <= 3; $i++) {
            $carte = new Carte();
            $carte->setNumC(10000000 + $i);
            $carte->setDateExp(new \DateTime('+2 years'));
            $carte->setTypeC($typeCs[$i-1]->getType());
            $carte->setStatutC('active');
            $carte->setAccount($comptesClients[$i - 1]);
            $carte->setType($typeCs[$i - 1]);
            $manager->persist($carte);
            $cartes[] = $carte;
        }

        // 10. Transaction
        for ($i = 1; $i <= 3; $i++) {
            $t = new Transaction();
            $t->setMontant(50 * $i);
            $t->setDateT(new \DateTime('now'));
            $t->setTypeT('paiement');
            $t->setStatutT('approuvée');
            $t->setIdC($cartes[$i - 1]);
            $manager->persist($t);
        }

        // 11. CategorieCredit
        $catCredits = [];
        for ($i = 1; $i <= 3; $i++) {
            $cc = new CategorieCredit();
            $cc->setNom("CreditCat $i");
            $cc->setMinMontant(1000);
            $cc->setMaxMontant(50000);
            $manager->persist($cc);
            $catCredits[] = $cc;
        }

        // 12. Credit
        $credits = [];
        for ($i = 1; $i <= 3; $i++) {
            $cred = new Credit();
            $cred->setMontantTotale(5000.0);
            $cred->setInteret(5);
            $cred->setDateC(new \DateTime('now'));
            $cred->setDureeTotale(12);
            $cred->setPayed(false);
            $cred->setAccepted(true);
            $cred->setCategorie($catCredits[$i - 1]);
            $cred->setCompte($comptesClients[$i - 1]); // OneToOne
            $manager->persist($cred);
            $credits[] = $cred;
        }

        // 13. Remboursement
        for ($i = 1; $i <= 3; $i++) {
            $remb = new Remboursement();
            $remb->setMontantR(500.0);
            $remb->setMontantRestant(4500.0);
            $remb->setDateR(new \DateTime('+1 month'));
            $remb->setDureeRestante(11);
            $remb->setCredit($credits[$i - 1]);
            $manager->persist($remb);
        }

        // 14. Cheque
        $cheques = [];
        for ($i = 1; $i <= 3; $i++) {
            $cheque = new Cheque();
            $cheque->setMontantC(200.0 * $i);
            $cheque->setDestinationC($users[($i) % 3]); // Rotate users
            $cheque->setCompteID($comptesClients[$i - 1]);
            $cheque->setDateEmission(new \DateTime('now'));
            $cheque->setIsfav(0);
            $manager->persist($cheque);
            $cheques[] = $cheque;
        }

        // 15. Reclamtion
        for ($i = 1; $i <= 3; $i++) {
            $rec = new Reclamtion();
            $rec->setCategorie("CatReclam $i");
            $rec->setStatutR("En cours");
            $rec->setChequeID($cheques[$i - 1]);
            $manager->persist($rec);
        }

        // 16. Virement
        for ($i = 1; $i <= 3; $i++) {
            $vir = new Virement();
            $vir->setCompteSource(str_pad((string)$i, 16, '0', STR_PAD_LEFT));
            $vir->setCompteDestination(str_pad((string)($i+1), 16, '0', STR_PAD_LEFT));
            $vir->setMontant(150.0 * $i);
            $vir->setDate(new \DateTime('now'));
            $vir->setHeure(new \DateTime('now'));
            $vir->setIdCompte($comptesClients[$i - 1]);
            $manager->persist($vir);
        }

        $manager->flush();
    }
}
