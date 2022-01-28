<?php
namespace frontend\controllers;

use Yii;
use GuzzleHttp\Client;
use yii\caching\TagDependency;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Exception\RequestException;

class AddressSearchController extends \yii\web\Controller
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();

        if(!empty($get) && !empty($get['action']) && $get['action'] == 'address') {

            $city = Yii::$app->user->identity->city->city;
            $query = Yii::$app->request->get('q', $city);

            try {
                $address = Yii::$app->cache->getOrSet(md5($query . $city), function () use ($query, $city) {
                    $request = new Client([
                        'base_uri' => 'https://geocode-maps.yandex.ru/1.x/'
                    ]);
                    $response = $request->request('GET', '', [
                        'query' => ['apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a', 'format' => 'json', 'geocode' => $query]
                    ]);

                    if ($response->getStatusCode() !== 200) {
                        return $this->asJson([['address' => '']]);
                    }

                    $content = $response->getBody()->getContents();
                    $response_data = Json::decode($content, true);

                    if ($error = ArrayHelper::getValue($response_data, 'error.info')) {
                        return $this->asJson([['address' => '']]);
                    }

                    $address = '';

                    if(strpos($response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'], $city)) {
                        $address = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'] . '; ' .
                            $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
                    }

                    return $address;

                }, 86400, new TagDependency(['tags' => 'geocoder']));

                return $this->asJson([['address' => $address]]);
            }
            catch (RequestException $e) {
                return $this->asJson([['address' => '']]);
            }

        }
    }

}
