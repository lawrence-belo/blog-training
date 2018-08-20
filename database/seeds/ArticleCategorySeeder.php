<?php

use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // These categories are samples for display purposes
        DB::table('article_category')->insert([
            [
                'name'            => 'books',
                'updated_user_id' => 1
            ],
            [
                'name'            => 'toys',
                'updated_user_id' => 1
            ]
        ]);
    }
}
