<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use App\Repositories\UserRepository;
use App\Repositories\CityRepository;

class SyncUsersAndCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:settle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomize relations between users and cities';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userRepository = new UserRepository();
        $users = $userRepository->all();
        $cityRepository = new CityRepository();
        $cities = $cityRepository->all()->pluck('id');

        $users->each(function ($user) use ($userRepository, $cities) {
            $randomCityIds = $cities->random(3);
            $jsonCityIds = json_encode($randomCityIds->all());
            $userRepository->update($user->id, ['city_ids' => $jsonCityIds]);
        });
    }
}
