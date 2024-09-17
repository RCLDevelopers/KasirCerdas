<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use App\Models\DataPembelian;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Resources\DataPembelianResource\Pages;
use App\Filament\Resources\DataPembelianResource\RelationManagers;


class DataPembelianResource extends Resource
{
    protected static ?string $model = DataPembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'laporan';
    protected static ?string $navigationLabel = 'Data Pembelian';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_struk')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('email_staff')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('tipe_pembayaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('diskon')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pajak')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_tagihan')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('yang_dibayarkan')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_struk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_staff')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diskon')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pajak')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_tagihan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('yang_dibayarkan')
                    ->numeric()
                    ->sortable(),
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
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
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
            'index' => Pages\ListDataPembelians::route('/'),
            'create' => Pages\CreateDataPembelian::route('/create'),
            'edit' => Pages\EditDataPembelian::route('/{record}/edit'),
        ];
    }
}
