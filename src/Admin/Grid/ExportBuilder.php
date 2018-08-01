<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Exports\DataSetExport;
use Arbory\Base\Admin\Grid as AdminGrid;
use Illuminate\Contracts\Support\Renderable;

class ExportBuilder implements Renderable
{
    /**
     * @var AdminGrid
     */
    protected $grid;

    /**
     * ExportBuilder constructor.
     * @param AdminGrid $grid
     */
    public function __construct(AdminGrid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return DataSetExport
     */
    public function render()
    {
        $items = $this->grid->getRows()->map(function (Row $row) {
            return $row->toArray();
        });

        return new DataSetExport($items);
    }
}