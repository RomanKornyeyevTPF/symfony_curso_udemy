<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PersonaEntityUpload
{
    #[Assert\NotBlank(message: 'El campo nombre es obligatorio')]
    protected $nombre;
    #[Assert\NotBlank(message: 'El campo email es obligatorio'), Assert\Email (message: 'El email {{ value }} no es un correo válido.')]
    protected $correo;
    #[Assert\NotBlank(message: 'El campo teléfono es obligatorio')]
    protected $telefono;
    #[Assert\Positive(message: 'Debe seleccionar un país')]
    protected $pais;
    #[Assert\NotBlank(message: 'Debe seleccionar un interés al menos')]
    protected $intereses = [];
    protected $foto;

    public function getNombre():string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre):void
    {
        $this->nombre = $nombre;
    }
    public function getCorreo():string
    {
        return $this->correo;
    }
    public function setCorreo(string $correo):void
    {
        $this->correo = $correo;
    }
    public function getTelefono():int
    {
        return $this->telefono;
    }
    public function setTelefono(int $telefono):void
    {
        $this->telefono = $telefono;
    }
    public function getPais():string
    {
        return $this->pais;
    }
    public function setPais(string $pais):void
    {
        $this->pais = $pais;
    }
    public function getIntereses(): array
    {
        return $this->intereses;
    }
    public function setIntereses(array $intereses): self
    {
        $this->intereses = $intereses;

        return $this;
    }
    public function getFoto(): string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }
}