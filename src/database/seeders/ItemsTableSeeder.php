<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item1 = Item::factory()->create([
            'name' => '腕時計',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
            'item_picture' => 'img/Armani+Mens+Clock.jpg',
            'condition' => '良好',
        ]);

        $item2 = Item::factory()->create([
            'name' => 'HDD',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
            'item_picture' => 'img/HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);


        $item3 = Item::factory()->create([
            'name' => '玉ねぎ3束',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
            'item_picture' => 'img/iLoveIMG+d.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);


        $item4 = Item::factory()->create([
            'name' => '革靴',
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
            'item_picture' => 'img/Leather+Shoes+Product+Photo.jpg',
            'condition' => '状態が悪い',
        ]);


        $item5 = Item::factory()->create([
            'name' => 'ノートPC',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'item_picture' => 'img/Living+Room+Laptop.jpg',
            'condition' => '良好',
        ]);


        $item6 = Item::factory()->create([
            'name' => 'マイク',
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'item_picture' => 'img/Music+Mic+4632231.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);


        $item7 = Item::factory()->create([
            'name' => 'ショルダーバッグ',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'item_picture' => 'img/Purse+fashion+pocket.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);

        $item8 = Item::factory()->create([
            'name' => 'タンブラー',
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'item_picture' => 'img/Tumbler+souvenir.jpg',
            'condition' => '状態が悪い',
        ]);

        $item9 = Item::factory()->create([
            'name' => 'コーヒーミル',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'item_picture' => 'img/Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好',
        ]);

        $item10 = Item::factory()->create([
            'name' => 'メイクセット',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'item_picture' => 'img/外出メイクアップセット.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);

        $fashion = Category::where('content', 'ファッション')->first();
        $electronics = Category::where('content', '家電')->first();
        $kitchen = Category::where('content', 'キッチン')->first();
        $mens = Category::where('content', 'メンズ')->first();
        $cosmetics = Category::where('content', 'コスメ')->first();

        $item1->categories()->attach($mens);
        $item2->categories()->attach($electronics);
        $item3->categories()->attach($kitchen);
        $item4->categories()->attach($fashion);
        $item5->categories()->attach($electronics);
        $item6->categories()->attach($electronics);
        $item7->categories()->attach($fashion);
        $item8->categories()->attach($kitchen);
        $item9->categories()->attach($electronics);
        $item10->categories()->attach($cosmetics);
    }
}
