<?php

namespace App\DataFixtures;

use App\Entity\Medecin;
use App\Service\DoctorConfigurationServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MedecinFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasherInterface,
        private DoctorConfigurationServiceInterface $doctorConfigurationService
        ) {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $tab = ["Jean Martin","Derode Cyril"];
        foreach($tab as $nom){
            $text = explode(" ",$nom);
            $medecin = new Medecin();
            $medecin->setNom($text[0]);
            $medecin->setPrenom($text[1]);
            $medecin->setEmail($text[0]."-".$text[1]."@gmail.com");
            $medecin->setPassword($this->userPasswordHasherInterface->hashPassword($medecin, "@Talmud89"));
            $medecin->setVerified(true);
            $medecin->setSpecialisation("Medecin Generaliste");
            $medecin->setAdresse("136 RUE DES MURLINS 45000 ORLEANS");
            $medecin->setRoles($medecin->getRoles());
            $manager->persist($medecin);
            $this->doctorConfigurationService->saveDoctorConfigurationByDefaut($medecin);
        }
        


        $manager->flush();
    }
}
