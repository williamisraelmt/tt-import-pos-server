<?php


namespace App\Console\Commands\Traits;

use App\Console\Commands\OdooConnectionModel;
use Edujugon\Laradoo\Exceptions\OdooException;
use Illuminate\Support\Collection;

trait OdooDownload
{
    /* @var OdooConnectionModel $connection */
    private $connection;

    /* @var $table string */
    private $table;

    /* @var $fields array */
    private $fields;

    /* @var $where array */
    private $where = [];

    /**
     * @return Collection
     * @throws OdooException
     */
    public function download()
    {
        $connection = $this->connection->getConnection();
        foreach ($this->where as $w) {
            $connection = $connection->where($w[0], $w[1], $w[2]);
        }
        return $connection->fields($this->fields)->get($this->table);
    }
}
