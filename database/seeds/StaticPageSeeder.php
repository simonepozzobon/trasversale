<?php
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('static_pages')->truncate();
        DB::table('static_pages')->insert([
            ['title' => 'chi siamo'],
            ['title' => 'formazione'],
            ['title' => 'educational'],
            ['title' => 'corsi'],
            ['title' => 'notizie'],
            ['title' => 'contatti']
        ]);

        DB::table('sub_pages')->truncate();
        DB::table('sub_pages')->insert([
            ['title' => 'il nostro approccio', 'static_page_id' => 1],
            ['title' => 'il team', 'static_page_id' => 1],
            ['title' => 'la rete', 'static_page_id' => 1],
            ['title' => 'la nostra storia', 'static_page_id' => 1],
            ['title' => 'certificazioni', 'static_page_id' => 1],
            ['title' => 'cv della societa', 'static_page_id' => 1],
            ['title' => 'archivio progetti', 'static_page_id' => 1],
        ]);
    }
}
