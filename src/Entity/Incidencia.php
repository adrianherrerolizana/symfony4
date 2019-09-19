<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncidenciaRepository")
 */
class Incidencia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     */
    private $resuelta;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $fechaResolucion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria")
     */
    private $categoria;

     /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="incidencia_tag",
     *      joinColumns={@ORM\JoinColumn(name="incidencia_id", referencedColumnName="id")},
     *      inverseJoinColumns={@orm\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $urlImagen;

    /**
     * @ORM\Column(type="string", nullable=true, length=15)
     */
    private $codigoIncidencia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="asignada")
    * @ORM\JoinColumn(name="asignada",referencedColumnName="id")
    */
   protected $asignada;


    public function __construct() {
        $this->tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion = null): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getResuelta(): ?bool
    {
        return $this->resuelta;
    }

    public function setResuelta(bool $resuelta): self
    {
        $this->resuelta = $resuelta;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getFechaResolucion()
                           	{
                           		return $this->fechaResolucion;
                           	}

	/**
	 * @param mixed $fechaResolucion
	 */
	public function setFechaResolucion($fechaResolucion): void
                           	{
                           		$this->fechaResolucion = $fechaResolucion;
                           	}

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function __toString()
	{
		return $this->getTitulo();
	}

	/**
	 * @return mixed
	 */
	public function getTag()
                  	{
                  		return $this->tag;
                  	}

	/**
	 * @param mixed $tag
	 */
	public function setTag($tag): void
                  	{
                  		$this->tag = $tag;
                  	}

	/**
	 * @return mixed
	 */
	public function getUrlImagen()
                  	{
                  		return $this->urlImagen;
                  	}

	/**
	 * @param mixed $urlImagen
	 */
	public function setUrlImagen($urlImagen): void
                  	{
                  		$this->urlImagen = $urlImagen;
                  	}

	/**
	 * @return mixed
	 */
	public function getCodigoIncidencia()
                  	{
                  		return $this->codigoIncidencia;
                  	}

	/**
	 * @param mixed $codigoIncidencia
	 */
	public function setCodigoIncidencia($codigoIncidencia): void
                  	{
                  		$this->codigoIncidencia = $codigoIncidencia;
                  	}

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getAsignada()
	{
		return $this->asignada;
	}

	/**
	 * @param mixed $asignada
	 */
	public function setAsignada($asignada): void
	{
		$this->asignada = $asignada;
	}

}
