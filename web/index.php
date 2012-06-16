<?php
//Setting PEAR libs in include_path to remove include_once errors in PEAR dependencies
set_include_path(get_include_path()
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Archive_Tar'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Console_Getopt'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/HTTP_Request2'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Mail_Mime'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Mail_mimeDecode'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Net_URL2'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/PEAR/PEAR-1.9.4'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/PEAR_Frontend_Gtk'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/PEAR_Frontend_Web'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/Structures_Graph'
    . PATH_SEPARATOR . __DIR__.'/../vendor/pear-pear/XML_Util'
);

//Making Error log in Azure easier to get to.
ini_set('error_log', __DIR__. '/../php_error.log');

use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';

//Storage config comes from SERVER variables
$config = array();
$config['account'] = $_SERVER["azure_storage_account"];
$config['key']     = $_SERVER["azure_storage_key"];

$app = new Silex\Application();
$app['debug']   = true;
$app['api']     = new Agg\Service\ApiReader();
$app['parser']  = new Agg\Service\StatsParser($app['api']);
$app['storage'] = new Agg\Service\Storage($config);

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views'));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//Homepage
$app->get('/', function (Silex\Application $app) {

    return $app['twig']->render('index.html.twig');

});

//Summary Generation
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

//Talk page
$app->get('/talk/{slug}', function (Silex\Application $app, $slug) {


    $aggregation = $app['storage']->retrieveAggregationBySlug($slug);

    $summary = $app['parser']->parseSummaryStats($aggregation->getTalks());

    //Render
    return $app['twig']->render('summary.html.twig', array(
        'summary'     => $summary,
        'aggregation' => $aggregation
    ));
})->bind('talk_show');

//List of aggregations
$app->get('/list', function (Silex\Application $app) {

    $aggregations = $app['storage']->retrieveAggregationList();

    //Render
    return $app['twig']->render('list.html.twig', array(
        'aggregations' => $aggregations
    ));

})->bind('list');

$app->run();
