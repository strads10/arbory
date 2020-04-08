<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Illuminate\Support\Collection;

/**
 * Class HasSelectOptions.
 */
trait HasSelectOptions
{
    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var bool
     */
    private $showEmptyOption = true;

    /**
     * @return $this
     */
    public function disableEmptyOption(): self
    {
        $this->showEmptyOption = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowEmptyOption(): bool
    {
        return $this->showEmptyOption;
    }

    /**
     * @param Collection|array $options
     * @return $this
     */
    public function options($options)
    {
        if (is_array($options)) {
            $options = new Collection($options);
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }
}
