<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\BlogCategory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportBlogs extends Command
{
    protected $signature = 'import:blogs';

    protected $description = 'Import Blogs from WordPress site';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client(['base_uri' => 'https://hellolaundry.co.uk/wp-json/wp/v2/','verify' => false]);

        // Fetch and store posts
        $page = 1;
        while(true) {
            try {
                $response = $client->request('GET', 'posts?_embed&per_page=100&page=' . $page);
                $posts = json_decode($response->getBody()->getContents(), true);
            } catch (ClientException $e) {
                // Break the loop if a page doesn't exist.
                break;
            }

            if (empty($posts)) {
                break;
            }

            foreach ($posts as $post) {
                // Only import posts with category_id and tags
//                $authorId = isset($post['author']) ? $post['author'] : null;
                $categoryId = isset($post['categories'][0]) ? $post['categories'][0] : null;
                $tags = isset($post['tags']) ? $post['tags'] : [];

                // check if post has categories and tags
                if ($categoryId && count($tags) > 0) {
                    // Download and store featured image
                    $featuredImage = null;
                    if (isset($post['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {
                        $imageUrl = $post['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];

                        $contents = file_get_contents($imageUrl);
                        $name = substr($imageUrl, strrpos($imageUrl, '/') + 1);
                        $targetPath = 'public/images/blogs/'.$post['slug'].'/'.$name;
                        Storage::put($targetPath, $contents);

                        $featuredImage = '/images/blogs/'.$post['slug'].'/'.$name;
                    }

                    $blogModel = Blog::updateOrCreate(
                        ['slug' => $post['slug']],
                        [
                            'title' => html_entity_decode($post['title']['rendered']),
                            'content' => html_entity_decode($post['content']['rendered']),
                            'author_id' => 1,
                            'featured_image' => $featuredImage,
                            'published_date' => date('Y-m-d H:i:s', strtotime($post['date'])),
                            'is_published' => $post['status'] == 'publish' ? 1 : 0,
                        ]
                    );

                    // Sync tags
                    $blogModel->categories()->sync($post['categories']);
                    $blogModel->tags()->sync($post['tags']);
                }
            }

            $page++;
        }

        echo "Blogs Imported Successfully!";
    }
}
