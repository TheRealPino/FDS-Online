<?php
date_default_timezone_set('UTC');
require 'vendor/autoload.php';


$app = new \Slim\Slim();

$app->container->singleton('twig', function ($c)
{
    $twig = new \Slim\Views\Twig();

    $twig->parserOptions = array('debug' => true, 'cache' => dirname(__FILE__) . '/cache');

    $twig->parserExtensions = array(new \Slim\Views\TwigExtension(),);

    $templatesPath = $c['settings']['templates.path'];
    $twig->setTemplatesDirectory($templatesPath);

    return $twig;
});

function version()
{
    return '1.0.0';
}

function sprint()
{
    $date1 = new DateTime('2015-09-02');
    $date2 = new DateTime();
    $interval = $date1->diff($date2);
    $status = array();
    $status['sprint'] = 1 + floor($interval->days / 14);
    $status['day'] = ($interval->days % 14) + 1;

    return $status;
}

function baseUrl()
{
    $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
    return str_replace("/index.php", "", $url);
}

$app->hook('slim.before', function () use ($app)
{
    $app->view()->appendData(array('baseUrl' => baseUrl()));
});

$app->get('/', function () use ($app)
{
    $app->render('home.php', array('sprint' => sprint(), 'version' => version()));
})->name('home');

$app->get('/list', function () use ($app)
{
    $app->render('list.php', array('sprint' => sprint(), 'version' => version()));
})->name('list');

$app->get('/login', function () use ($app)
{
    $app->render('login.php', array('sprint' => sprint(), 'version' => version()));
})->name('login');

// TODO
$app->get('/create-account', function () use ($app)
{
    $app->render('createAccount.php', array('sprint' => sprint(), 'version' => version()));
})->name('create-account');

// TODO
$app->get('/forgot-password', function () use ($app)
{
    $app->render('forgotPassword.php', array('sprint' => sprint(), 'version' => version()));
})->name('forgot-password');

$app->run();
?>
