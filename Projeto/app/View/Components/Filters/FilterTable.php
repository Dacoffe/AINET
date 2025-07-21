<?php

namespace App\View\Components\Products;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        Request $request,
        public bool $showAsc = true,
        public bool $showDesc = true,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.products.table');
    }
}
