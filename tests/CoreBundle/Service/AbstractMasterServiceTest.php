<?php

namespace CoreBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractMasterService extends KernelTestCase
{
    /** @var ContainerInterface */
    private $container;

    /**
     * setUp
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    /**
     * @param $serviceName
     * @return object
     */
    public function get($serviceName)
    {
        return $this->container->get($serviceName);
    }

    /**
     * @param int $nbJour
     * @return \DateTime
     */
    public function getDateDiffDateJour($nbJour)
    {
        // cast du nombre de jour en entier
        $nbJour = (int) $nbJour;

        // récupération de la date du jour
        $dateJour = new \DateTime();

        // ajout de la durée à la date du jour si $nbJour est positif
        if ($nbJour > 0) {
            // création de la chaine d'interpretation de la durée à soustraire
            $diff = "P{$nbJour}D";
            $dateJour->add(new \DateInterval($diff));
        }

        // on soustrait la duréé car inférieure à 0
        if ($nbJour < 0) {
            // utilisation de la valeur absolue de $nbJour
            $nbJour = abs($nbJour);
            // création de la chaine d'interpretation de la durée à soustraire
            $diff = "P{$nbJour}D";
            $dateJour->sub(new \DateInterval($diff));
        }

        return $dateJour;
    }

    /**
     * @uses vérifie que la date retournée par la méthode est bien inférieure à la date
     * du jour, dans le cas ou le paramètre du nombre de jour est -1
     * @covers AbstractMasterService::getDateDiffDateJour
     */
    public function testGetDateDiffDateJour1()
    {
        // création de la date du jour
        $dateJour = new \DateTime();

        // utilisation de la méthode pour obtenir une date inférieure à la date du jour
        // de moins jour
        $dateTest = $this->getDateDiffDateJour(-1);

        // test de l'infériorité
        $this->assertTrue($dateTest < $dateJour);
    }

    /**
     * @uses vérifie que la date retournée par la méthode est bien égale à la date
     * du jour, dans le cas ou le paramètre du nombre de jour est 0
     * @covers AbstractMasterService::getDateDiffDateJour
     */
    public function testGetDateDiffDateJour2()
    {
        // création de la date du jour
        $dateJour = new \DateTime();

        // utilisation de la méthode pour obtenir une date égale à la date du jour
        $dateTest = $this->getDateDiffDateJour(0);

        // test de l'égalité
        $this->assertTrue($dateTest->getTimestamp() === $dateJour->getTimestamp());
    }

    /**
     * @uses vérifie que la date retournée par la méthode est bien supérieure à la date
     * du jour, dans le cas ou le paramètre du nombre de jour est 1
     * @covers AbstractMasterService::getDateDiffDateJour
     */
    public function testGetDateDiffDateJour3()
    {
        // création de la date du jour
        $dateJour = new \DateTime();

        // utilisation de la méthode pour obtenir une date supérieure à la date du jour
        // de plus un jour
        $dateTest = $this->getDateDiffDateJour(1);

        // test de l'égalité
        $this->assertTrue($dateTest > $dateJour);
    }
}
