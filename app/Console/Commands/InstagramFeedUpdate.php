<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstagramFeedUpdate extends Command
{

    const USER_NAME = 'laznepramen';
    const FEED_FILE_NAME = 'instagram-feed.json';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:instagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $feedFileFullPath = storage_path(self::FEED_FILE_NAME);

        try {
            $instaResult = file_get_contents('https://www.instagram.com/'. self::USER_NAME .'/?__a=1');
            if ( self::isJson($instaResult) ) {
                file_put_contents($feedFileFullPath, $instaResult);
                chmod($feedFileFullPath, 0644);
                $this->info('Feed has been saved');
            } else {
                $this->alert('Feed error. Seems instagram sucks');
            }
        } catch (Exception $e) {
            $this->alert('something went wrong');
        }

    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
