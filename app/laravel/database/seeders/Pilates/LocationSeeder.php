<?php

namespace Database\Seeders\Pilates;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pilates\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create([
            'name'=>'遠浅公民館',
            'policy'=>'free',
            'address'=>'〒059-1433 北海道勇払郡安平町遠浅１２５−１',
            'base_fee'=>'0',
            'map_url'=>'https://maps.app.goo.gl/5xw6EvLVEbQXa1qF6',
            'is_active'=>true,
        ]);
        Location::create([
            'name'=>'町民会館',
            'policy'=>'shared',
            'address'=>'〒059-1502 北海道勇払郡安平町早来北進１０２−４',
            'base_fee'=>'400',
            'map_url'=>'https://maps.app.goo.gl/tYqtUgUUpiK3MzKd7',
            'is_active'=>true,
        ]);
        Location::create([
            'name'=>'安平町スポーツセンター',
            'policy'=>'shared',
            'address'=>'〒059-1502 北海道勇払郡安平町早来北進１０２−５',
            'base_fee'=>'200',
            'map_url'=>'https://maps.app.goo.gl/sV1VfRBifaF6FEUr6',
            'is_active'=>true,
        ]);
        Location::create([
            'name'=>'beauty Ruby',
            'policy'=>'paid',
            'address'=>'〒053-0015 北海道苫小牧市本幸町２丁目３−１７ グランドール本幸町',
            'base_fee'=>'500',
            'map_url'=>'https://maps.app.goo.gl/hsSLRavaoggXrwGt6',
            'is_active'=>true,
        ]);
    }
}
