<?php

namespace App\Filament\Clusters\AcProducts;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class AcProductsCluster extends Cluster
{
   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

   protected static ?string $navigationLabel = 'Product Management';
   protected static ?int $navigationSort = 30;

   public static function getClusterBreadcrumb(): ?string
   {
      return 'Products';
   }
}
