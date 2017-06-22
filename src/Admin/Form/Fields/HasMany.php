<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use Closure;
use CubeSystems\Leaf\Admin\Form\Fields\Concerns\HasRelationships;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Form\Fields\Renderer\NestedFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

/**
 * Class HasMany
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class HasMany extends AbstractField
{
    use HasRelationships;

    /**
     * @var Closure
     */
    protected $fieldSetCallback;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * AbstractRelationField constructor.
     * @param string $name
     * @param Closure $fieldSetCallback
     */
    public function __construct( $name, Closure $fieldSetCallback )
    {
        parent::__construct( $name );

        $this->fieldSetCallback = $fieldSetCallback;
    }

    /**
     * @return bool
     */
    public function canAddRelationItem()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canSortRelationItems()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canRemoveRelationItems()
    {
        return true;
    }

    /**
     * @return Element|string
     */
    public function render()
    {
        return ( new NestedFieldRenderer( $this, $this->orderBy ) )->render();
    }

    /**
     * @param $index
     * @param Model $model
     * @return FieldSet
     */
    public function getRelationFieldSet( $model, $index )
    {
        $fieldSet = new FieldSet( $model, $this->getNameSpacedName() . '.' . $index );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        $fieldSet->prepend(
            ( new Hidden( $model->getKeyName() ) )
                ->setValue( $model->getKey() )
        );

        if( $this->isSortable() && $this->getOrderBy() )
        {
            $fieldSet->prepend(
                ( new Hidden( $this->getOrderBy() ) )
                    ->setValue( $model->{$this->getOrderBy()} )
            );
        }

        return $fieldSet;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {

    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        $items = (array) $request->input( $this->getNameSpacedName(), [] );

        foreach( $items as $index => $item )
        {
            $relatedModel = $this->findRelatedModel( $item );

            if( filter_var( array_get( $item, '_destroy' ), FILTER_VALIDATE_BOOLEAN ) )
            {
                $relatedModel->delete();

                continue;
            }

            $relatedFieldSet = $this->getRelationFieldSet(
                $relatedModel,
                $index
            );

            foreach( $relatedFieldSet->getFields() as $field )
            {
                $field->beforeModelSave( $request );
            }

            $relation = $this->getRelation();

            if( $relation instanceof MorphMany )
            {
                $relatedModel->setAttribute( $relation->getMorphType(), get_class( $this->getModel() ) ); // TODO:
            }

            $relatedModel->setAttribute( $relation->getForeignKeyName(), $this->getModel()->getKey() );

            $relatedModel->save();

            foreach( $relatedFieldSet->getFields() as $field )
            {
                $field->afterModelSave( $request );
            }
        }
    }


    /**
     * @param $variables
     * @return Model
     */
    private function findRelatedModel( $variables )
    {
        $relation = $this->getRelation();

        $relatedModelId = array_get( $variables, $relation->getRelated()->getKeyName() );

        return $relation->getRelated()->findOrNew( $relatedModelId );
    }

    /**
     * @return bool
     */
    protected function isSortable(): bool
    {
        foreach( $this->fieldSet->getFields() as $field )
        {
            if( $field instanceof Sortable && $field->getSortableField() === $this )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return $this
     */
    public function setOrderBy( string $orderBy )
    {
        $this->orderBy = $orderBy;

        return $this;
    }
}
