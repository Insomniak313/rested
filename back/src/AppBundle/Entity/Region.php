<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Region
 * @package AppBundle\Entity
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegionRepository")
 */
class Region
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
     * @ORM\Column(name="code", type="string", length=2)
     */
    private $code;
	
	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="Departement", mappedBy="region", cascade={"persist", "remove"})
	 */
	private $departements;
	
	public function __construct()
	{
		$this->departements = new ArrayCollection;
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
     * @param string $nom
     * @return Region
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Assignation du nom
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
	
	/**
	 * Récupération du code région
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

    /**
     * Assignation du code région
     * @param string $code
     * @return Region
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
    
	/**
	 * Ajoute un département à la collection
	 * @param Departement $departement
	 * @return $this
	 */
	public function addDepartement(Departement $departement)
	{
		$this->departements->add($departement);
		
		return $this;
	}
	
	/**
	 * Supprime un département de la collection
	 * @param Departement $departement
	 * @return $this
	 */
	public function removeDepartement(Departement $departement)
	{
		$this->departements->removeElement($departement);
		
		return $this;
	}
	
	/**
	 * Supprime tous les départements de la collection
	 * @return $this
	 */
	public function clearDepartements()
	{
		$this->departements->clear();
		
		return $this;
	}
}
