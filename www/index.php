<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;



$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => dirname( __DIR__ ) . '/views',
));


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'appros_master',
        'user'      => 'root',
        'password'  => '123456',
        'charset'   => 'utf8',
    ),
));



/**
* Функция проверки, авто-регистрации и авто-авторизации социального пользовалетя
* 
*/
function is_alid_social_user()
{
    // TODO: Написать процесс авто регистрации и авторизации социального пользователя
}




/**
* Первый этап.
* Стартовая страница "/", выдается только по запросу GET, иначе выдается 403.
* Стартовая страница "/" - доступна без авторизации.
*/
$app->get('/', function() use ($app) {
    
    $sql = "SELECT * FROM som_products LIMIT 18";
    
    $products = $app['db']->fetchAll($sql);
    
    return $app['twig']->render('index.twig', array(
        'products' => $products,
    ));
});

$app->get('/{partition}', function($partition) use ($app) {
    return $app['twig']->render($partition . '.twig');
});


/**
* Второй этап.
* Все остальный доступные страницы выдаются только для GET запросов и только авторизованным пользователям.
* Используется авто-авторизация.
* @todo Написать шаблон для роутинга
*/
$app->get('/', function () use ($app) {
    if (!is_alid_social_user()) {
        $app->abort(403, "Access denied");
    }
    
    // TODO: Вызов всех доступных контроллеров
});


/**
* Третий этап.
* Запросы к API допустимы только для POST и только для авторизованных пользователей.
* Используется авто-авторизация.
*/
$app->post('/api', function () use ($app) {
    if (!is_alid_social_user()) {
        $app->abort(403, "Access denied");
    }
    
    // TODO: Вызов контроллера API
});


/**
* Для всего остального выдается 403 ошибка.
*/
$app->match('/', function () use ($app) {
    $app->abort(403, "Access denied");
});


$app->run();