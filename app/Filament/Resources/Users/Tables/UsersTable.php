<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
         ->query(User::query()->withCount('posts')) 
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('user_name')
                    ->label('Username')
                    ->searchable(),
                   
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('City')
                    ->searchable(),
                TextColumn::make('posts_count')
                ->label('Posts Count'),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->options(
                        User::query()
                            ->pluck('city', 'city')
                            ->filter()
                            ->unique()
                    ),
                // ->relationship('users', 'city'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('UpdateUsers')
                    ->label('Import Users Api Data')
                    ->action(function () {
                        try{
                        $res = Http::get('https://jsonplaceholder.typicode.com/users');
                        if($res->failed())
                        {
                            throw new \Exception('Failed to fetch uses API data');
                        }
                        $users = $res->json();
                        if(empty($users)|| !is_array($users)){
                            throw new \Exception('Invalid data received');
                        }
                        foreach ($users as $user) {
                            if (empty($user['email'])) {
                                continue;
                            }
                            User::updateOrInsert(
                                ['email' => $user['email']],
                                [
                                    'external_id' => $user['id'],
                                    'name' => $user['name'],
                                    'user_name' => $user['username'],
                                    'phone' => $user['phone'] ?? null,
                                    'company_name' => $user['company']['name'] ?? null,
                                    'city' => $user['address']['city'] ?? null,
                                    'password' => bcrypt($user['username'] ?? 'user123'),

                                ]
                            );
                        }
                        Notification::make()
                        ->title('Users API data imported successfully')
                        ->success()
                        ->body('All valid user in the API')
                        ->send();
                        } catch(\Exception $e){
                            Notification::make()
                            ->title('Import Faild')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();

                        }
                        
                    }),
                    // ->requiresConfirmation()
                    // ->successNotificationTitle('Recent User Data updated successfully'),
            ]);
    }
}
