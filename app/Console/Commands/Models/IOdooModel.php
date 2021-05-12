<?php


namespace App\Console\Commands\Models;


interface IOdooModel
{

    public function toStoreAsArray() : array;
}
