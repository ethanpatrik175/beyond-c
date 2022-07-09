<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HomeBanner extends Component
{
    public $bannerTitle;
    public function __construct($bannerTitle)
    {
        $this->bannerTitle = $bannerTitle;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home-banner');
    }
}
