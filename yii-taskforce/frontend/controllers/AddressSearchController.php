<?php
namespace frontend\controllers;

use Yii;
use GuzzleHttp\Client;
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

            $q = Yii::$app->request->get('q', 'Челябинск');

            try {
                $request = new Client([
                    'base_uri' => 'https://geocode-maps.yandex.ru/1.x/'
                ]);
                $response = $request->request('GET', '', [
                    'query' => ['apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a', 'format' => 'json', 'geocode' => $q]
                ]);

                if ($response->getStatusCode() !== 200) {

                }

                $content = $response->getBody()->getContents();
                $response_data = Json::decode($content, true);

                if ($error = ArrayHelper::getValue($response_data, 'error.info')) {

                }

                $address = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'] . '; ' .
                    $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];

                return $this->asJson([['address' => $address]]);
            }
            catch (RequestException $e) {
                return $this->asJson([['address' => '']]);
            }

        }
    }

}
