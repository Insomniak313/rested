<?php

namespace AppBundle\Command;

use AppBundle\Entity\Departement;
use AppBundle\Entity\Region;
use AppBundle\Service\CurlService;
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
    const STEP_FLUSH = 50;

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
        // On va calculer combien de temps le traitement dure
    	$startTime = microtime(true);

    	// On récupère le conteneur pour pouvoir ensuite appeler les différents services
    	$container = $this->getContainer();

        // = nb fois ou on fait avancer la barre d'avancement, pas besoin de s'embetter pour ce POC
		$nb_lignes = 1;

        // Création de la progressbar
		$progressBar = new ProgressBar($output, $nb_lignes);
		$progressBar->setFormat(
			"<fg=white;bg=cyan> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-20s%  %memory:20s%"
		);
		// Mise en forme
		$progressBar->setBarCharacter('<fg=green>⚬</>');
		$progressBar->setEmptyBarCharacter("<fg=red>⚬</>");
		$progressBar->setProgressCharacter("<fg=green>➤</>");

		// Fréquence de redessin de la barre
		$progressBar->setRedrawFrequency(10);

		// On lance la progressbar
		$progressBar->start();

		// On récupère l'entity manager de doctrine
		$em = $container->get('doctrine')->getEntityManager();

        /**
         * On récupère notre service CurlService
         * @var CurlService $curlService
         */
		$curlService = $container->get('app_bundle.curl_service');

		// Récupération des regions depuis l'API
        $curlService->setUrls(['https://geo.api.gouv.fr/regions?fields=nom,code']);

        // Création du Curl
        $jsonRegions = $curlService->init()->run();
        $jsonRegions = isset($jsonRegions[0][0]) ? $jsonRegions[0][0] : [];
        $arrayRegions = json_decode($jsonRegions, true);

        // On crée un tableau associatif des régions indexées par leur code
        $arrayAssocRegions = [];
        foreach ($arrayRegions as $dataRegion)
        {
            $nom = isset($dataRegion['nom']) ? $dataRegion['nom'] : '';
            $code = isset($dataRegion['code']) ? $dataRegion['code'] : '';

            $arrayAssocRegions[$code] = (new Region)
                ->setNom($nom)
                ->setCode($code);
        }

        // Récupération des départements depuis l'API
        $curlService->reset()->setUrls(['https://geo.api.gouv.fr/departements?fields=nom,code,codeRegion']);

        // Création d'un deuxième Curl
        $jsonDepartements = $curlService->init()->run();
        $jsonDepartements = isset($jsonDepartements[0][0]) ? $jsonDepartements[0][0] : [];
        $arrayDepartements = json_decode($jsonDepartements, true);

        // On parcourt le tableau des départements
        foreach ($arrayDepartements as $dataDepartement)
        {
            $nom = isset($dataDepartement['nom']) ? $dataDepartement['nom'] : '';
            $code = isset($dataDepartement['code']) ? $dataDepartement['code'] : '';

            $codeRegion = isset($dataDepartement['codeRegion']) ? $dataDepartement['codeRegion'] : '';

            // Si une région liée au département existe, on va la stocker
            if (isset($arrayAssocRegions[$codeRegion]))
            {
                /**
                 * @var Region $region
                 */
                $region = $arrayAssocRegions[$codeRegion];

                $departement = (new Departement)
                    ->setNom($nom)
                    ->setCode($code)
                    ->setRegion($region);

                $region
                    ->addDepartement($departement);

            }
        }

        // Compteur utilisé pour flusher la base de données tous les 50 persist
        $index = 0;

        // On parcourt le tableau associatif des régions pour persister les entités
        foreach($arrayAssocRegions as $region)
        {
            // On persiste les régions
            $em->persist($region);

            // Et les départements qui sont liés
            $departements = $region->getDepartements();
            foreach ($departements as $departement)
            {
                $em->persist($departement);

                // On flush la base de données tous les 50 persist
                if($index % self::STEP_FLUSH == 0)
                {
                    $em->flush();
                    $em->clear();
                }

                $index++;
            }

            // On flush la base de données tous les 50 persist
            if($index % self::STEP_FLUSH == 0)
            {
                $em->flush();
                $em->clear();
            }

            $index++;
        }

        // On flush la base de données à la fin dans tous les cas
        $em->flush();
        $em->clear();

        // Calcul de la durée du traitement
		$durationSec = microtime(true) - $startTime;
		$durationMin = floor($durationSec / 60);
		$durationSec = number_format($durationSec - $durationMin * 60, 2, ',', ' ');

		// Et affichage dans la progressbar
		$progressBar->setMessage("Temps de traitement: $durationMin min $durationSec sec.", 'status');
		$progressBar->finish();
    }

}
