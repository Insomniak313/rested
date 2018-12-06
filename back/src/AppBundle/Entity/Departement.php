<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Departement
 * @package AppBundle\Entity
 * @ORM\Table(name="departement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepartementRepository")
 */
class Departement
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
     * @ORM\Column(name="code", type="string", length=3)
     */
    private $code;
	
	/**
	 * @var Region
	 * @ORM\ManyToOne(targetEntity="Region", inversedBy="departements")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $region;

    /**
     * Fonction permettant de transformer l'objet Departement en tableau
     * @return array
     */
    public function toArray()
    {
        return [
            'nom' => $this->getNom(),
            'code' => $this->getCode()
        ];
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
     * @return Departement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }
	
	/**
	 * Récupération du code département
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}
    
    /**
     * Assignation du code département
     * @param string $code
     * @return Departement
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
	
	/**
	 * Récupération de la région
	 * @return Region
	 */
	public function getRegion()
	{
		return $this->region;
	}
	
	/**
	 * Assignation de la région
	 * @param Region $region
	 * @return Departement
	 */
	public function setRegion(Region $region)
	{
		$this->region = $region;
		
		return $this;
	}
}
