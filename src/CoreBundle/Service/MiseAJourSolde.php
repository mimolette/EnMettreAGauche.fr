<?php

namespace CoreBundle\Service;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Operation\AjustementService;
use CoreBundle\Service\Operation\RenouvellementService;

/**
 * MiseAJourSolde class file
 *
 * PHP Version 5.6
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
/**
 * MiseAJourSolde class
 *
 * @category Service
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 * @uses ce service à pour but mettre à jour les différents soldes des compte
 * suite à des ajustements, opérations, modifications d'opération.
 */
class MiseAJourSolde
{
    /** @var CompteService */
    private $compteService;

    /** @var AjustementService */
    private $ajustementService;

    /** @var RenouvellementService */
    private $renouvellementService;

    /**
     * MiseAJourSolde constructor.
     * @param CompteService         $compteService
     * @param AjustementService     $ajustementService
     * @param RenouvellementService $renouvellementService
     */
    public function __construct(
        CompteService $compteService,
        AjustementService $ajustementService,
        RenouvellementService $renouvellementService
    ) {
        $this->compteService = $compteService;
        $this->ajustementService = $ajustementService;
        $this->renouvellementService = $renouvellementService;
    }

    /**
     * @param AjustementSolde $ajustementSolde
     * @throws \MasterBundle\Exception\EmagException
     */
    public function parAjustement(AjustementSolde $ajustementSolde)
    {
        // appel aux services de compte et d'ajustement
        $cService = $this->compteService;
        $aService = $this->ajustementService;

        // récupération du compte associé à l'ajustement
        $compte = $aService->getCompte($ajustementSolde);

        // vérification si le compte est actif
        $cService->isCompteActif($compte);

        // vérification si le compte autorise les ajustements
        $cService->isAutoriseAuxAjustements($compte);

        // si la mise à jour à été effectuée, mise a jour de l'attribut solde avant
        $soldeAvant = $compte->getSolde();
        $ajustementSolde->setSoldeAvant($soldeAvant);

        // si toute les vérifications sont passées, mise à jour du solde du compte
        $nouveauSolde = $ajustementSolde->getSoldeApres();
        $cService->setNouveauSolde($nouveauSolde, $compte);
    }

    /**
     * @param Renouvellement $renouvellement
     * @throws \MasterBundle\Exception\EmagException
     */
    public function parRenouvellement(Renouvellement $renouvellement)
    {
        // appel aux service de compte et d'ajustement
        $cService = $this->compteService;
        $rService = $this->renouvellementService;
        
        // récupération du compte associé au renouvellement
        $compte = $rService->getCompte($renouvellement);
        
        // vérification si le compte est actif
        $cService->isCompteActif($compte);
        
        // si toute les vérifications sont passées, mise à jour du nombre de ticket 
        // du compte, ainsi que de sont solde
        $cService->addNbTicket($renouvellement->getNbTickets(), $compte);
    }

    public function parOperation(AbstractOperation $operation)
    {
        // appel aux service d'opération
        
        
    }
}