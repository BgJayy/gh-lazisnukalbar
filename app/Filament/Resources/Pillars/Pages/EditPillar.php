<?php

namespace App\Filament\Resources\Pillars\Pages;

use App\Filament\Resources\Pillars\PillarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPillar extends EditRecord
{
    protected static string $resource = PillarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
