<?php
use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';

$config = array();
$config['account'] = 'aggregate';
$config['key']     = '0UYTEmZxL2e5yp1yiLy1Vhcs38z6KjDk+oYse22XAS7uali4grJ5dB0AuHNA3N923QtOVa/X3cUtEWdlpHABqg==';

$app = new Silex\Application();
$app['debug']   = true;
$app['api']     = new Agg\Service\ApiReader();
$app['parser']  = new Agg\Service\StatsParser($app['api']);
$app['storage'] = new Agg\Service\Storage($config);

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views'));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->get('/', function (Silex\Application $app) {

    return $app['twig']->render('index.html.twig');

});

$app->post('/generate', function (Silex\Application $app, Request $request) {

    $talks = $request->get('talks');

    //Get Primary Talk data
    $talkData = $app['api']->getTalkData($talks[0]);

    if ($talkData === null) throw new UnexpectedValueException("Invalid talk");

    $speakerData = $app['api']->getSpeakerData($talkData->speakers[0]->speaker_uri);

    if ($speakerData === null) throw new UnexpectedValueException("Invalid speaker");

    //New Aggregation
    $agg = new \Agg\Entity\Aggregation();
    $agg->setTitle($talkData->talk_title);
    $agg->setTalks($talks);
    $agg->setSpeakerName($speakerData->full_name);
    $agg->setSpeakerUrl($speakerData->website_uri);

    //Persist
    $app['storage']->storeAggregation($agg);

    //Redirect
    return \Symfony\Component\HttpFoundation\RedirectResponse::create("/talk/".$agg->getSlug());
});

$app->get('/talk/{slug}', function (Silex\Application $app, $slug) {


    $aggregation = $app['storage']->retrieveAggregationBySlug($slug);

    $summary = $app['parser']->parseSummaryStats($aggregation->getTalks());

    //Render
    return $app['twig']->render('summary.html.twig', array(
        'summary'     => $summary,
        'aggregation' => $aggregation
    ));
})->bind('talk_show');

$app->get('/list', function (Silex\Application $app) {

    $aggregations = $app['storage']->retrieveAggregationList();

    //Render
    return $app['twig']->render('list.html.twig', array(
        'aggregations' => $aggregations
    ));

})->bind('list');

$app->run();
