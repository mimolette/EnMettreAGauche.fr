<?php

namespace CoreBundle\Service;

use CoreBundle\Entity\AbstractOperation;
use CoreBundle\Entity\AjustementSolde;
use CoreBundle\Entity\Renouvellement;
use CoreBundle\Service\Compte\CompteService;
use CoreBundle\Service\Master\AbstractPersistentService;
use CoreBundle\Service\Operation\AjustementService;
use CoreBundle\Service\Operation\OperationService;
use CoreBundle\Service\Operation\RenouvellementService;
use Doctrine\ORM\EntityManager;
use MasterBundle\Exception\EmagException;

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
 * @uses ce service à pour but de mettre à jour les différents soldes des comptes
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

    /** @var OperationService */
    private $operationService;

    /**
     * MiseAJourSolde constructor.
     * @param CompteService         $compteService
     * @param AjustementService     $ajustementService
     * @param RenouvellementService $renouvellementService
     * @param OperationService      $operationService
     */
    public function __construct(
        CompteService $compteService,
        AjustementService $ajustementService,
        RenouvellementService $renouvellementService,
        OperationService $operationService
    ) {
        $this->compteService = $compteService;
        $this->ajustementService = $ajustementService;
        $this->renouvellementService = $renouvellementService;
        $this->operationService = $operationService;
    }

    /**
     * @param AjustementSolde $ajustementSolde
     * @throws EmagException
     * @return array des éléments à persiter
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

        // si toutes les vérifications sont passées, mise à jour du solde du compte
        $nouveauSolde = $ajustementSolde->getSoldeApres();
        $cService->setNouveauSolde($nouveauSolde, $compte);

        // retourne les éléments à persiter dans un tableau
        return [$ajustementSolde, $compte];
    }

    /**
     * @param Renouvellement $renouvellement
     * @throws EmagException
     * @return array des éléments à persiter
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
        // du compte, ainsi que de son solde
        $cService->addNbTicket($renouvellement->getNbTickets(), $compte);

        // retourne les éléments à persiter dans un tableau
        return [$renouvellement, $compte];
    }

    /**
     * @param AbstractOperation $operation
     * @throws EmagException
     * @return array des éléments à persiter
     */
    public function parOperation(AbstractOperation $operation)
    {
        // appel aux service d'opération et de compte
        $oService = $this->operationService;
        $cService = $this->compteService;

        // tentative de deviner le signe de l'opération
        $oService->devinerSigneOperation($operation);

        // vérification si l'opération est valide (si ce n'est pas le cas, une exception
        // sera levée
        $oService->isOperationValide($operation);

        // vérifie si l'opération doit être comptabilisée
        $comptabilise = $oService->isOperationDoitEtreComptabilisee($operation);

        // si l'opération doit être comptabilisée tout de suite
        if ($comptabilise) {
            // mise à jour du ou des soldes de compte
            $elementsAPersiter = $cService->miseAjourSoldeParOperation($operation);
            
            // ajout du compte
        } else {
            // on ne persiste que l'opération
            $elementsAPersiter = [$operation];
        }

        // retourne les éléments à persiter dans un tableau
        return $elementsAPersiter;
    }
}