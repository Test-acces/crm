<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsGridWidget extends Widget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.quick-actions-grid';
}
