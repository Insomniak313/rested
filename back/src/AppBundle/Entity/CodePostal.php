<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CodePostal
 * @package AppBundle\Entity
 * @ORM\Table(name="code_postal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CodePostalRepository")
 */
class CodePostal
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
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;
	
	/**
	 * @var Commune
	 * @ORM\ManyToOne(targetEntity="Commune", inversedBy="codePostaux")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $commune;

    /**
     * Récupération de l'identifiant
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
	 * Récupération du code postal
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}
	
	/**
	 * Assignation du code postal
     * @param string $code
     * @return CodePostal
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
    
	/**
	 * Récupération de la commune
	 * @return Commune
	 */
	public function getCommune()
	{
		return $this->commune;
	}
	
	/**
	 * Assignation de la commune
	 * @param Commune $commune
	 * @return CodePostal
	 */
	public function setCommune(Commune $commune)
	{
		$this->commune = $commune;
		
		return $this;
	}
}
