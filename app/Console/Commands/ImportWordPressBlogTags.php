<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ImportWordPressBlogTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tags';

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
        $tags = [];
        $page = 1;
        $perPage = 50;  // you can set up to 100

        do {
            $response = $client->request('GET', 'https://hellolaundry.co.uk/wp-json/wp/v2/tags', [
                'query' => [
                    'per_page' => $perPage,
                    'page' => $page,
                ]
            ]);

            $tags = json_decode($response->getBody()->getContents(), true);

            foreach ($tags as $tag) {
                Tag::updateOrCreate(
                    ['id' => $tag['id']],
                    [
                        'slug' => $tag['slug'],
                        'title' => html_entity_decode($tag['name'])
                    ]
                );
            }

            $page++;
        } while(count($tags) === $perPage);  // if the count of tags is less than per page, we have reached the last page


    }
}
