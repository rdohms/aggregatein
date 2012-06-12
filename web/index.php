<?php
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app['api']   = new \Agg\Service\ApiReader();
$app['parser'] = new Agg\Service\StatsParser($app['api']);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/', function (Silex\Application $app) {

    return $app['twig']->render('index.html.twig');

});

$app->get('/select-title/{id}', function (Silex\Application $app, $id) {

});

$app->post('/generate', function (Silex\Application $app, Request $request) {

    $talkUrlBase = "http://api.joind.in/v2.1/talks/";
    $talks = $request->get('talks');

    //Get Primary Talk data
    $primaryTalkId = $talks[0];
    $json = file_get_contents($talkUrlBase . $primaryTalkId);

    $primaryTalkData = json_decode($json);

    $agg = new \Agg\Entity\Aggregation();
    $agg->setTitle($primaryTalkData->talk_title);
    $agg->setTalks($talks);

    //Persist
});

$app->get('/talk/{slug}', function (Silex\Application $app, $slug) {

    $talks = array('6681', '6437');
    $summary = $app['parser']->parseSummaryStats($talks);

    var_dump($summary, $summary->getStats()->getAverageRating(), $summary->getStats()->getTotalCount());
    //Retrieve summary by slug

    //Get/Parse Data

    //Render

});


$app->run();