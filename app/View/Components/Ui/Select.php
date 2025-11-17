<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class Select extends Component
{
    public string $name;
    public ?string $label;
    public string $placeholder;

    public function __construct(
        string $name,
        string $placeholder = 'Selecciona una opción...',
        string $label = null
    ) {
        $this->name        = $name;
        $this->placeholder = $placeholder;
        $this->label       = $label;
    }


    public function render(): View|Closure|string
    {
        return view('components.ui.select');
    }
}
