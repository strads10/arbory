<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Renderer\SelectFieldRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BelongsTo
 * @package Arbory\Base\Admin\Form\Fields
 */
class BelongsTo extends Select
{
    use HasRelationships;

    protected $rendererClass = SelectFieldRenderer::class;

    public function getValue()
    {
        $foreignKey = $this->getRelation()->getForeignKey();
        $value = $this->getModel()->getAttribute( $foreignKey );

        return $value ?? $this->getDefaultValue();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function getOptions():Collection
    {
        return $this->getRelatedItems();
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $this->getModel()->setAttribute(
            $this->getRelation()->getForeignKey(),
            $request->input( $this->getNameSpacedName() )
        );
    }
}
