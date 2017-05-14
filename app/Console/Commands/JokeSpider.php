<?php

namespace App\Console\Commands;

use App\Joke;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;

use Illuminate\Database\QueryException;

class JokeSpider extends Command {
  //http://japi.juhe.cn/joke/content/list.from
  private $totalPageCount;
  private $counter = 1;
  private $concurrency = 7;

  protected $signature = 'spider:joke';
  protected $description = 'Command description';

  public function __construct()
  {
    parent::__construct();

    //dd($this->appKey);
  }


  public function handle()
  {

    $uri    = 'http://japi.juhe.cn/joke/content/';
    $logPath = 'joke_spider/spider_log';
    $timeStorePath = 'joke_spider/earliest_time';

    $appKey = env('JUHE_API_KEY');
    if (Storage::disk('local')->exists($timeStorePath)) {
      $time = Storage::disk('local')->get($timeStorePath);
      if($time == null) $time = time();
    } else {
      $time = time();
    }


    $earliestTime = $time;
    $totalPage    = 50;

    $client = new Client([
      'base_uri' => $uri,
      'timeout'  => 2.0
    ]);
    $this->info('Begin to get data before ' . date('Y-m-d H:i:s', $time) . ' with ' . $totalPage . ' pages data, 20 data per page, total' . 20 * $totalPage . 'data');
    for ($page = 1; $page <= $totalPage; $page++) {
      $this->info('requesting data of page ' . $page);
      $response = $client->request('GET', 'list.from', [
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
        Storage::disk('local')->append($logPath, date('Y-m-d H:i:s', $time)." ".$res->reason);
        $this->info($res->reason);
        continue;
      }

      $jokes = $res->result->data;

      foreach ($jokes as $key => $joke) {
        $params['content']            = $joke->content;
        $params['hashId']             = $joke->hashId;
        $params['origin_unix_time']   = $joke->unixtime;
        $params['origin_update_time'] = $joke->updatetime;
        if(Joke::where('hashId', $params['hashId'])->get()->isEmpty()) {
          try {
            Joke::create($params);
          } catch (QueryException $queryException) {
            $this->warn($queryException->getMessage());
            Storage::disk('local')->put($logPath, '['.date('Y-m-d H:i:s', time()).']'.$queryException->getMessage());
          }catch (Exception $exception) {
            $this->warn($exception->getMessage());
            Storage::disk('local')->put($logPath, '['.date('Y-m-d H:i:s', time()).']'.$exception->getMessage());
          }finally {
            $this->info('Stored page ' . $page . '\'s ' . ($key + 1) . 'th data');
            $earliestTime = $params['origin_unix_time'];
            Storage::disk('local')->put($timeStorePath, $earliestTime+100);
          }


        } else {
          Storage::disk('local')->append($logPath, '['.date('Y-m-d H:i:s', time()).']'." ignore repeated data, hashId：".$params['hashId']);
          $this->info(" ignore repeated data, hashId：".$params['hashId']);
        }

      }
      $this->info("wait 10 seconds...");
      sleep(10);
    }


    Storage::disk('local')->put($timeStorePath, $earliestTime+100);

    $this->info('Complete, update data to ' . date('Y-m-d H:i:s', $earliestTime));
    Storage::disk('local')->append($logPath, '['.date('Y-m-d H:i:s', time()).']'.'Complete, update data to' . date('Y-m-d H:i:s', $earliestTime));
  }

}
