<?php

namespace AppBundle\Command;

use AppBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadDataCommand
 * @package AppBundle\Command
 */
class LoadDataCommand extends ContainerAwareCommand
{
	/**
	 * Configuration de la commande
	 */
    protected function configure()
    {
        $this
            ->setName('app:load-data')
            ->setDescription('Charge les données issues de l\'API https://api.gouv.fr/api/api-geo.html')
        ;
    }
	
	/**
	 * Méthode appelée à l'execution de la commande
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$startTime = microtime(true);
    	$container = $this->getContainer();
	
		$nb_lignes = 1; // = nb fois ou on fait advance

		$progressBar = new ProgressBar($output, $nb_lignes);
		$progressBar->setFormat(
			"<fg=white;bg=cyan> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-20s%  %memory:20s%"
		);
		$progressBar->setBarCharacter('<fg=green>⚬</>');
		$progressBar->setEmptyBarCharacter("<fg=red>⚬</>");
		$progressBar->setProgressCharacter("<fg=green>➤</>");

		$progressBar->setRedrawFrequency(10);

		$progressBar->start();
	
		$em = $container->get('doctrine')->getEntityManager();
		
		//while(true)
		//{
		//
		//	$em->persist($rei[$index]);
		//
		//	if($index == 0)
		//	{
		//		$em->flush();
		//		$em->clear();
		//	}
		//	$progressBar->advance();
		//}

		$em->flush();
		$em->clear();

		$durationSec = microtime(true) - $startTime;
		$durationMin = floor($durationSec / 60);
		$durationSec = number_format($durationSec - $durationMin * 60, 2, ',', ' ');

		$progressBar->setMessage("Temps de traitement: $durationMin min $durationSec sec.", 'status');
		$progressBar->finish();
    }

}
