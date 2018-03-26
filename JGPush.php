<?php
namespace liuhui5354\jgpush;

use JPush\Client as JPushClient;
use JPush\Exceptions\APIConnectionException;
use JPush\Exceptions\APIRequestException;
use JPush\PushPayload;

class JGPush{

    protected $app_key;
    protected $master_secret;
    protected $client=null;
    public $sound='02_APEX.m4r';
    protected $production;

    function __construct($app_key,$master_secret,$production=false,$sound='')
    {
        $this->app_key=$app_key;
        $this->master_secret=$master_secret;
        $this->client=new JPushClient($this->app_key, $this->master_secret);
        if($sound){
            $this->sound=$sound;
        }
        $this->production=$production;
    }

    public function sendByTel($tel,$title,$content,$extras,$sendTime=''){
        try {
            $builder = $this->client->push()
                ->setPlatform('all')
                ->addAlias($tel)
                ->setNotificationAlert($title)
                ->iosNotification($content, array(
                    'sound' => $this->sound,
                    'category' => 'iOS category',
                    'extras' => $extras,
                ))
                ->androidNotification($content, array(
                    'title' => $title,
                    'extras' => $extras
                ))
                ->message($content, array(
                    'title' => $title,
                    'extras' => $extras,
                ))
                ->options(array(
                    'time_to_live'=>3600,
                    'apns_production' => $this->production,
                ))->build();
            if($sendTime==''){
                /* @var $builder PushPayload */
                $response=$builder->send();
            }else{
                $response=$this->client->schedule()->createSingleSchedule("定时发送的任务", $builder, array("time"=>$sendTime));
            }
        } catch (APIConnectionException $e) {
            $response= $e;
        } catch (APIRequestException $e) {
            $response= $e;
        }

        return $response;
    }

    public function sendByTag($tag,$title,$content,$extras,$sendTime=''){
        try {
            $builder = $this->client->push()
                ->setPlatform('all')
                ->addTag($tag)
                ->setNotificationAlert($title)
                ->iosNotification($content, array(
                    'sound' => $this->sound,
                    'category' => 'iOS category',
                    'extras' => $extras,
                ))
                ->androidNotification($content, array(
                    'title' => $title,
                    'extras' => $extras
                ))
                ->message($content, array(
                    'title' => $title,
                    'extras' => $extras,
                ))
                ->options(array(
                    'time_to_live'=>3600,
                    'apns_production' => $this->production,
                ))->build();
            if($sendTime==''){
                /* @var $builder PushPayload */
                $response=$builder->send();
            }else{
                $response=$this->client->schedule()->createSingleSchedule("定时发送的任务", $builder, array("time"=>$sendTime));
            }
        } catch (APIConnectionException $e) {
            $response= $e;
        } catch (APIRequestException $e) {
            $response= $e;
        }

        return $response;
    }
}
