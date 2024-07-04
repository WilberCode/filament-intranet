<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\HolidayResource\Pages;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;
    protected static ?string $navigationLabel = 'Vacaciones';
    protected static ?string $navigationIcon = 'heroicon-s-rocket-launch';

    public static function getNavigationBadge(): ?string
    {
        return  parent::getEloquentQuery()->where('user_id', Auth::user()->id )->where('type','pending')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id )->where('type','pending')->count() >0? 'warning' : 'info';
    }
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of holidays pending';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship('calendar', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('day')
                    ->required(),
            ]) ;
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id )->orderBy('id', 'desc');
    }
   /*  public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';

        return $data;
    } */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('user.name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('day')
                ->date()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('type')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'approved' => 'info',
                    'decline' => 'danger',
                })
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                ->options([
                    'decline' => 'Decline',
                    'approved' => 'Approved',
                    'pending' => 'In pending',
                ])
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            // 'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
