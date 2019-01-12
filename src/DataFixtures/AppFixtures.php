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
        $subCategories = [];

        for($i=1;$i<=5;$i++){

            $user = new User;
            $user->setUsername('Etudiant' . $i);
            $user->setEmail('etudiant' . $i . '@desco.fr');
            $user->setTel('0' . $i . '0203040' . $i);
            if($i === 1){
                $roles = ['ROLE_USER', 'ROLE_ADMIN'];
            }else{
                $roles = ['ROLE_USER'];
            }
            $user->setRoles($roles);

            $plainPassword = 'oui';
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
            $subCategorie->setCategory($categories[array_rand($categories)]);

            $manager->persist($subCategorie);

            $subCategories[] = $subCategorie;

        }

        $reductions = ['-10%', '-20%', '-30%', '-40%', '-50%', '-60%', '-70%'];

        $localisations = ['44.842323, -0.578925', '44.839089, -0.572373', '44.840542, -0.581955', '44.842422, -0.5827',
            '44.835575, -0.57506', '44.838952, -0.579781', '44.862249, -0.549995', '44.897391, -0.5615540',
            '44.831187, -0.572127', '44.84314,  -0.578933'];

        for($i=0;$i<=9;$i++){

            $adresse = new Address();
            $adresse->setName('Magasin' . $i);
            $adresse->setLocation($i . ' Rue Tonbouctou, 33000 Bordeaux');
            $adresse->setWebsite('https://www.meilleur-magasin-de-bordeaux-' . $i . '.fr');
            $adresse->setCoordinates($localisations[$i]);
            $adresse->setTel('0' . $i . '0203040' . $i);
            $adresse->setDiscount($reductions[array_rand($reductions)]);
            $adresse->setStatus('valid');
            $adresse->setSubCategory($subCategories[array_rand($subCategories)]);
            $adresse->setImage(' ');
            $manager->persist($adresse);

            $adresses[] = $adresse;

        }

        $manager->flush();
    }
}
