<?php

namespace CoreBundle\Tests\Entity;

/**
 * CategorieTest class file
 *
 * PHP Version 5.6
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
use CoreBundle\Entity\Categorie;

/**
 * CategorieTest class
 *
 * @category Test
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class CategorieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Categorie
     * @covers Categorie::getId
     * @covers Categorie::isActive
     */
    public function testVideCategorie()
    {
        // création d'une catégorie
        $cat = new Categorie();
        $this->assertNull($cat->getId());
        
        // vérification si la catégorie est bien active par défaut
        $this->assertTrue($cat->isActive());

        // renvoi de l'objet Categorie
        return $cat;
    }

    /**
     * @depends testVideCategorie
     * @return Categorie
     */
    public function testCategorieAvecEnfants(Categorie $categorie)
    {
        // création de deux catégories enfants
        $catEnfant1 = new Categorie();
        $catEnfant2 = new Categorie();

        // affectation des enfants
        $categorie->addEnfant($catEnfant1);
        $categorie->addEnfant($catEnfant2);

        return $categorie;
    }

    /**
     * @uses vérifie si le changement de couleur d'un categorie se propage aux enfants
     * @param Categorie $categorie
     * @covers Categorie::setCouleur
     * @depends testCategorieAvecEnfants
     */
    public function testSetCouleur(Categorie $categorie)
    {
        // changement de la couleur de la catégorie parent
        $categorie->setCouleur('#454545');

        // vérification si les enfants possèdent aussi cette couleur
        /** @var Categorie $catEnfant */
        foreach ($categorie->getEnfants() as $catEnfant) {
            // test si l'enfant possèdent la même couleur
            $this->assertEquals('#454545', $catEnfant->getCouleur());
        }
    }

    /**
     * @uses vérifie si le changement d'état de la categorie se propage bien aux enfants
     * @param Categorie $categorie
     * @covers Categorie::setActive
     * @depends testCategorieAvecEnfants
     */
    public function testSetActive(Categorie $categorie)
    {
        // changement d'état de la catégorie parent
        $categorie->setActive(false);

        // vérification si les enfants possèdent aussi cet état
        /** @var Categorie $catEnfant */
        foreach ($categorie->getEnfants() as $catEnfant) {
            // test si l'enfant possèdent la même couleur
            $this->assertFalse($catEnfant->isActive());
        }
    }

    /**
     * @uses vérifie que l'ajout d'un enfant affecte sa couleur et son état
     * @param Categorie $categorie
     * @covers Categorie::addEnfant
     * @depends testVideCategorie
     */
    public function testaddEnfant(Categorie $categorie)
    {
        // changement d'état et couleur de la catégorie parent
        $categorie->setActive(false);
        $categorie->setCouleur('#125896');

        // création d'un enfant
        $catEnfant = new Categorie();

        // affectation de l'enfant
        $categorie->addEnfant($catEnfant);

        // vérification si l'enfants possèdent la même couleur et état
        $this->assertFalse($catEnfant->isActive());
        $this->assertEquals('#125896', $catEnfant->getCouleur());
    }
}
