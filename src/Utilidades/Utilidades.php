<?php

namespace App\Utilidades;
use App\Utilidades\Utilidades;

class Utilidades
{
    public static function saludo(string $nombre)
    {
        return "hola {$nombre}";
    }

    public function saludo2(string $nombre)
    {
        return "hola {$nombre}";
    }
}