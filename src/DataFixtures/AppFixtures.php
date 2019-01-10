<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        //lors de l'instanciation, on stocke dans l'attribut encode, l'objet qui va nous permettre d'encoder les mdp
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $users = [];
        $categories = [];
        $adresses = [];
        $subCategory = [];

        for($i=1;$i<=5;$i++){

            $user = new User;
            $user->setUsername('Etudiant' . $i);
            $user->setEmail('etudiant' . $i . '@desco.fr');
            if($i === 1){
                $roles = ['ROLE_USER', 'ROLE_ADMIN'];
            }else{
                $roles = ['ROLE_USER'];
            }
            $user->setRole($roles);

            $plainPassword = '123';
            $mdpencoded = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($mdpencoded);
            $user->setAvatar('');

            $manager->persist($user);

            $users[] = $user;

        }

        for($i=1;$i<=10;$i++){

            $categorie = new Category();
            $categorie->setLabel('categorie' . $i);

            $manager->persist($categorie);

            $categories[] = $categorie;

        }

        for($i=1;$i<=10;$i++){

            $subCategorie = new SubCategory();
            $subCategorie->setLabel('subCategorie' . $i);

            $manager->persist($subCategorie);

            $subCategory[] = $subCategorie;

        }


        for($i=1;$i<=5;$i++){

            $adresse = new Address();
            $adresse->setName('Magasin' . $i);
            $adresse->setLocation('26 Rue de la Porte Dijeaux, 33000 Bordeaux' . $i);
            $adresse->setWebsite('https://www.lacoste.com/fr/' . $i);
            $adresse->setCoordinates('44.181094, 0.596378' . ' - ' . $i);
            $adresse->setTel('0556444383' . ' - ' . $i);
            $adresse->setDiscount('tout gratuit, on est sympa' . $i);
            $adresse->setStatus('valid');
            $adresse->setAverage('3');
            $adresse->setSubCategory('');

            $manager->persist($adresse);

            $adresses[] = $adresse;

        }

        $manager->flush();
    }
}
