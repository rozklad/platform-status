<?php namespace Sanatorium\Status\Repositories;

class StatusableRepository
{
    protected $statusables;

    public function registerStatusable($statusable = null)
    {
        $this->statusables[] = $statusable;
    }

    public function getStatusables()
    {
        return $this->statusables;
    }
}
