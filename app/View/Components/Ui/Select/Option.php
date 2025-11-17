<?php

namespace App\View\Components\Ui\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Option extends Component
{
    public string $value;
    public ?string $label;

    public function __construct(string $value, string $label = null)
    {
        $this->value = $value;
        $this->label = $label;
    }
    public function render(): View|Closure|string
    {
        return view('components.ui.select.option');
    }
}
