<?php

namespace App\Filament\Clusters\Task;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class TaskCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    protected static ?string $navigationLabel = 'Task Management';
    protected static ?int $navigationSort = 20;

    public static function getClusterBreadcrumb(): ?string
    {
        return 'Tasks';
    }
}
