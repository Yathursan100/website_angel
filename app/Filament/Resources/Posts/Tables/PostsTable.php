<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Posts;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('user.name')
                ->label('User Name'),
                IconColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return $record->is_published ? true : false;
                    })
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-pencil')
                    ->tooltip(function ($record) {
                        return $record->is_published ? 'Published' : 'Draft';
                    }),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->default(now()),
                   // ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name'),
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
                Action::make('Update Post')
                    ->label('Import Posts Api Data')
                    ->action(function () {
                        $res = Http::get('https://jsonplaceholder.typicode.com/posts');
                        $posts = $res->json();
                        foreach ($posts as $post) {
                            if (empty($post['id'])) {
                                continue;
                            }
                            $title = $post['title'] ?? '';
                            $slug = Str::slug($title);
                            $user = User::where('external_id', $post['userId'])->first();
                            Posts::updateOrInsert(
                                ['external_id' => $post['id']],
                                [
                                    'user_id' => $user ? $user->id : null,
                                    'title' => $post['title'],
                                    'slug' => $slug,
                                    'body' => $post['body'] ?? null,
                                    'is_published' => true,
                                ]
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->successNotificationTitle('Recent Post Details updated successfully'),
            ]);
    }
}
