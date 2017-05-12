<?php

namespace App\Console\Commands;

use App\Image;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;

class ImageSpider extends Command {
  //http://japi.juhe.cn/joke/content/list.from

  protected $signature = 'spider:image';
  protected $description = 'Command description';

  public function __construct()
  {
    parent::__construct();

    //dd($this->appKey);
  }


  public function handle()
  {
   
    $uri           = 'http://japi.juhe.cn/joke/';
    $logPath       = 'joke_spider/image_spider_log';
    $timeStorePath = 'joke_spider/image_earliest_time';

    $appKey = env('JUHE_API_KEY');
    if (Storage::disk('local')->exists($timeStorePath)) {
      $time = Storage::disk('local')->get($timeStorePath);
      if ($time == null) $time = time();
    } else {
      $time = time();
    }


    $earliestTime = $time;
    $totalPage    = 20;

    $client = new Client([
      'base_uri' => $uri,
      'timeout'  => 2.0
    ]);
    $this->info('Begin to get data before ' . date('Y-m-d H:i:s', $time) . ' with ' . $totalPage . ' pages data，20 data per page，total' . 20 * $totalPage . 'data');
    for ($page = 1; $page < $totalPage; $page++) {
      $this->info('requesting data of page ' . $page);
      $response = $client->request('GET', 'img/list.from', [
          'query' => [
            'sort'     => 'desc',
            'page'     => $page,
            'pagesize' => 20,
            'time'     => $time,
            'key'      => $appKey
          ]
        ]

      );

      $res = \GuzzleHttp\json_decode($response->getBody()->getContents());

      if ($res->error_code != 0) {
        Storage::disk('local')->append($logPath, date('Y-m-d H:i:s', $time) . " " . $res->reason);
        $this->info($res->reason);
        continue;
      }

      $images = $res->result->data;

      foreach ($images as $key => $image) {
        $params['content']            = $image->content;
        $params['hashId']             = $image->hashId;
        $params['url']                = $image->url;
        $params['origin_unix_time']   = $image->unixtime;
        $params['origin_update_time'] = $image->updatetime;
        if (Image::where('hashId', $params['hashId'])->get()->isEmpty()) {
          try {
            Image::create($params);
          } catch (QueryException $queryException) {
            $this->warn($queryException->getMessage());
            Storage::disk('local')->put($logPath, '[' . date('Y-m-d H:i:s', time()) . ']' . $queryException->getMessage());
          } catch (Exception $exception) {
            $this->warn($exception->getMessage());
            Storage::disk('local')->put($logPath, '[' . date('Y-m-d H:i:s', time()) . ']' . $exception->getMessage());
          } finally {
            $this->info('Stored page ' . $page . '\'s ' . ($key + 1) . 'th data');
            $earliestTime = $params['origin_unix_time'];
            Storage::disk('local')->put($timeStorePath, $earliestTime + 100);
          }


        } else {
          Storage::disk('local')->append($logPath, '[' . date('Y-m-d H:i:s', time()) . ']' . " ignore repeated data，hashId：" . $params['hashId']);
          $this->info(" ignore repeated data，hashId：" . $params['hashId']);
        }

      }
      $this->info("wait 10 seconds...");
      sleep(10);
    }


    Storage::disk('local')->put($timeStorePath, $earliestTime + 100);

    $this->info('Complete, update data to ' . date('Y-m-d H:i:s', $earliestTime));
    Storage::disk('local')->append($logPath, '[' . date('Y-m-d H:i:s', time()) . ']' . 'Complete, update data to' . date('Y-m-d H:i:s', $earliestTime));
  }

}
