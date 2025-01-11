<?php

namespace App\Filament\Resources\DataDaerahResource\Pages;

use App\Filament\Resources\DataDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataDaerah extends EditRecord
{
    protected static string $resource = DataDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
