<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimesheetResource\Pages;
use App\Filament\Resources\TimesheetResource\RelationManagers;
use App\Models\Calendar;
use App\Models\Timesheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship(name:'calendar', titleAttribute:'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship(name:'user', titleAttribute:'name')
                    ->required(),
                Forms\Components\Select::make('type')
                ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                    ]) ,
                Forms\Components\DateTimePicker::make('day_in')
                    ->required(),
                Forms\Components\DateTimePicker::make('day_out')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 // Show calendar name
                Tables\Columns\TextColumn::make('calendar.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_in')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('day_out')
                    ->dateTime()
                    ->sortable()
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
                SelectFilter::make('calendar')
                    ->relationship('calendar', 'name'),
                SelectFilter::make('type')
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                    ])


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTimesheets::route('/'),
            'create' => Pages\CreateTimesheet::route('/create'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
