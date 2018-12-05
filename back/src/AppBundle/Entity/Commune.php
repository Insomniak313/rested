<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Commune
 * @package AppBundle\Entity
 * @ORM\Table(name="commune")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommuneRepository")
 */
class Commune
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="population", type="integer")
     */
    private $population;
	
	/**
	 * @var Departement
	 * @ORM\ManyToOne(targetEntity="Departement", inversedBy="communes")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $departement;
	
	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="CodePostal", mappedBy="commune", cascade={"persist", "remove"})
	 */
	private $codesPostaux;
	
	public function __construct()
	{
		$this->codesPostaux = new ArrayCollection;
	}
	
    /**
	 * Récupération de l'identifiant
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
	 * Récupération du nom
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
	
	/**
	 * Assignation du nom
	 * @param string $nom
	 * @return Commune
	 */
	public function setNom($nom)
	{
		$this->nom = $nom;
		
		return $this;
	}
	
	/**
	 * Récupération du code commune
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}
	
    /**
     * Assignation du code commune
     * @param string $code
     * @return Commune
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
	
	/**
	 * Récupération de la population
	 * @return int
	 */
	public function getPopulation()
	{
		return $this->population;
	}

    /**
     * Assignation de la population
     * @param int $population
     * @return Commune
     */
    public function setPopulation($population)
	{
		$this->population = $population;
	
		return $this;
	}
	
	/**
	 * Récupération du département
	 * @return Departement
	 */
	public function getDepartement()
	{
		return $this->departement;
	}
	
	/**
	 * Assignation du département
	 * @param Departement $departement
	 * @return Commune
	 */
	public function setDepartement(Departement $departement)
	{
		$this->departement = $departement;
		
		return $this;
	}
	
	/**
	 * Ajoute un code postal à la collection
	 * @param CodePostal $codePostal
	 * @return $this
	 */
	public function addCodePostal(CodePostal $codePostal)
	{
		$this->codesPostaux->add($codePostal);
		
		return $this;
	}
	
	/**
	 * Supprime un code postal de la collection
	 * @param CodePostal $codePostal
	 * @return $this
	 */
	public function removeCodePostal(CodePostal $codePostal)
	{
		$this->codesPostaux->removeElement($codePostal);
		
		return $this;
	}
	
	/**
	 * Supprime tout les codes postaux de la collection
	 * @return $this
	 */
	public function clearCodePostaux()
	{
		$this->codesPostaux->clear();
		
		return $this;
	}
}
