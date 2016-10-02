<?php

namespace MasterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * DoctrineFixturesReloadCommand class file
 *
 * PHP Version 5.6
 *
 * @category Command
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */

/**
 * DoctrineFixturesReloadCommand class
 *
 * @category Command
 * @author   Guillaume ORAIN <g.orain@sdvi.fr>
 */
class DoctrineFixturesReloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('doctrine:database:reload')
            ->setDescription('Rechargement intégrale de la base de données avec chargement des fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Etes vous sûre de vouloir recharger la base de données ? (y/n)', false);

        // demande de confirmation à l'utilisateur
        $confirmation = $helper->ask($input, $output, $question);

        // si l'utilisateur souhaite comfirmer l'éxécution de la commande
        if ($confirmation) {
            // éxecution de la commande de suppression de la base de données :
            $deleteCommand = $this->getApplication()->find('doctrine:database:drop');

            $deleteArguments = array(
                'command' => 'doctrine:database:drop',
                '--force' => true,
            );

            $deleteInput = new ArrayInput($deleteArguments);
            $deleteCommand->run($deleteInput, $output);

            // éxecution de la commande de création de la base de données :
            $createCommand = $this->getApplication()->find('doctrine:database:create');

            $createArguments = array(
                'command' => 'doctrine:database:create',
            );

            $createInput = new ArrayInput($createArguments);
            $createCommand->run($createInput, $output);

            // éxecution de la commande de mise à jour du schéma de la base de données :
            $schemaCommand = $this->getApplication()->find('doctrine:schema:update');

            $schemaArguments = array(
                'command' => 'doctrine:schema:update',
                '--force' => true,
            );

            $schemaInput = new ArrayInput($schemaArguments);
            $schemaCommand->run($schemaInput, $output);

            // éxecution de la commande de chargement des fixtures :
            $fixturesCommand = $this->getApplication()->find('doctrine:fixtures:load');

            $fixturesArguments = array(
                'command' => 'doctrine:fixtures:load',
            );

            $fixturesInput = new ArrayInput($fixturesArguments);
            $fixturesInput->setInteractive(false);
            $fixturesCommand->run($fixturesInput, $output);

            $message = 'Rechargement terminé.';
        } else {
            $message = 'Annulation ...';
        }

        // renvoi du message dans la console
        $output->writeln($message);
    }

}
