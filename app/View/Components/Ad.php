<?php

namespace App\View\Components;

use App\Models\Ad as AdModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ad extends Component
{
    public ?AdModel $ad;
    public string $placement;
    public string $wrapperClass;

    public function __construct(string $placement, string $wrapperClass = 'ad-container my-8 flex justify-center w-full overflow-hidden')
    {
        $this->placement = $placement;
        $this->wrapperClass = $wrapperClass;
        try {
            $this->ad = AdModel::getForPlacement($placement);
        } catch (\Throwable $e) {
            $this->ad = null;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.ad');
    }
}
