<?php
namespace App\Entity;

class PersonaEntity
{
    protected $nombre;
    protected $correo;
    protected $telefono;
    protected $pais;
    protected $intereses = [];

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
}