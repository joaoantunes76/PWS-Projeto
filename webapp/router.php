<?php
/**
 * Created by PhpStorm.
 * User: smendes
 * Date: 02-05-2016
 * Time: 11:18
 */
use ArmoredCore\Facades\Router;

/****************************************************************************
 *  URLEncoder/HTTPRouter Routing Rules
 *  Use convention: controllerName@methodActionName
 ****************************************************************************/

Router::get('/',			'HomeController/index');
Router::get('home/',		'HomeController/index');
Router::get('home/index',	'HomeController/index');
Router::get('home/start',	'HomeController/start');
Router::get('home/login',	'HomeController/login');
Router::get('home/about', 'HomeController/about');

Router::get('game/', 'GameController/index');
Router::get('game/index', 'GameController/index');
Router::get('game/register', 'GameController/register');
Router::post('game/createAccount', 'GameController/createAccount');
Router::get('game/login', 'GameController/login');
Router::post('game/authenticate', 'GameController/authenticate');
Router::post('game/logout', 'GameController/logout');
Router::post('game/game', 'GameController/game');
Router::get('game/backoffice', 'GameController/backoffice');
Router::get('game/changebanstatus', 'GameController/changebanstatus');
Router::get('game/top10', 'GameController/top10');

Router::post('gameLogic/lancarDados', 'GameLogicController/lancarDados');








/************** End of URLEncoder Routing Rules ************************************/