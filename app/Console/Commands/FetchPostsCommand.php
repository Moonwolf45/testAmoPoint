<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

#[Signature('posts:api-fetch')]
#[Description('Получаем посты из JSONPlaceholder API и сохраняем в базу данных')]
class FetchPostsCommand extends Command
{
    protected function pluralForm($number, $forms) {
        $cases = [2, 0, 1, 1, 1, 2];

        return $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }

    /**
     * Execute the console command.
     *
     * @throws ConnectionException
     */
    public function handle(): int
    {
        $this->info('Получаем посты из API...');

        $response = Http::get('https://jsonplaceholder.typicode.com/posts?_limit=10');

        if (!$response->successful()) {
            $this->error('Ошибка получение записей из API.');

            return Command::FAILURE;
        }

        $posts = $response->json();
        $savedCount = 0;

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['user_id' => $post['userId'], 'title' => $post['title']],
                [
                    'body' => $post['body'],
                    'fetched_at' => now(),
                ]
            );

            $savedCount++;
        }

        $saveTitle = ['Сохранен', 'Сохранено', 'Сохранено'];
        $postTitle = ['пост', 'поста', 'постов'];

        $this->info($this->pluralForm($savedCount, $saveTitle) . " {$savedCount} " . $this->pluralForm($savedCount, $postTitle));

        return Command::SUCCESS;
    }
}
