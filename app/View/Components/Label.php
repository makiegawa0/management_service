<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    /** @var string */
    public $name;

    /** @var bool */
    public $required;

    /**
     * Create the component instance.
     */
    public function __construct(string $name, bool $required = false)
    {
        $this->name = $name;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.label');
    }
}
