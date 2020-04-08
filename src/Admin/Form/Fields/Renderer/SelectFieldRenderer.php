<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\SelectControl;

class SelectFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    /**
     * @return \Arbory\Base\Html\Elements\Content|\Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        /**
         * @var SelectControl
         */
        $control = $this->getControl();
        $control = $this->configureControl($control);

        $control->setOptions($this->getOptions());
        $control->setSelected($this->field->getValue());

        $element = $control->element();

        if ($this->field->isMultiple()) {
            $control->setMultiple(true);

            $name = $control->getName();
            $element->addAttributes([
                'multiple' => '',
                'name' => $name.'[]',
            ]);
        }

        return $control->render($element);
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = $this->field->getOptions();

        if ($this->field->isShowEmptyOption()) {
            $options->prepend('', '');
        }

        return $options->all();
    }
}
