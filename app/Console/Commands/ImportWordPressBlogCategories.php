<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\Tag;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportWordPressBlogCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client(['verify' => false]);
        $categories = [];
        $page = 1;
        $perPage = 50;  // you can set up to 100

        do {
            $response = $client->request('GET', 'https://hellolaundry.co.uk/wp-json/wp/v2/categories', [
                'query' => [
                    'per_page' => $perPage,
                    'page' => $page,
                ]
            ]);

            $categories = json_decode($response->getBody()->getContents(), true);

            foreach ($categories as $category) {
                BlogCategory::updateOrCreate(
                    ['id' => $category['id']],
                    [
                        'slug' => $category['slug'],
                        'title' => html_entity_decode($category['name'])
                    ]
                );
            }

            $page++;
        } while(count($categories) === $perPage);  // if the count of tags is less than per page, we have reached the last page

    }
}
