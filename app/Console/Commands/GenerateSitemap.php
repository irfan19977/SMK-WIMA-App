<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for the website';

    public function handle()
    {
        $domain = 'https://www.smkpgri-lawang.sch.id';
        
        $sitemap = Sitemap::create()
            ->add(Url::create($domain)
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create($domain . '/about')
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create($domain . '/berita')
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Add all published news with their slugs
        $news = News::where('is_published', true)->get();
        
        foreach ($news as $item) {
            $sitemap->add(Url::create($domain . '/berita/' . $item->slug)
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($item->updated_at));
        }

        // Add other static pages
        $staticPages = [
            'pendaftaran' => 0.8,
            'kimia-industri' => 0.7,
            'teknik-komputer-jaringan' => 0.7,
            'teknik-bisnis-sepeda-motor' => 0.7,
            'teknik-kendaraan-ringan' => 0.7,
            'contact' => 0.6,
        ];

        foreach ($staticPages as $page => $priority) {
            $sitemap->add(Url::create($domain . '/' . $page)
                ->setPriority($priority)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
        return Command::SUCCESS;
    }
}
