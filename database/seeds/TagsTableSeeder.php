<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Tag::count()) {
            $tags = array(
                'online payments', 'no verification needed', 'no receipt needed', 'physical cards only', 'e-codes only',
                'receipt required', 'cash only', 'photo id required', 'verified paypal only', 'cash only', 'verified paypal only',
                'e-gift card', 'physical cards only', 'no screenshot', 'alberto', 'imps transfer', 'fast', 'no bargain', 'no vpn',
                'no scammers', 'fast payment', 'no negotiation', 'negotiation accepted', 'wema', 'ibn', 'good trace', 'identity'
            );

            foreach ($tags as $tag){
                Tag::create(['name' => $tag]);
            }
        }
    }
}
