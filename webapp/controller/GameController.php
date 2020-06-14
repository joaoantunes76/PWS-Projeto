<?php
use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Post;

class GameController extends BaseController
{
    public function index(){
        if(Session::has('player'))
        {
            return View::make('game.index');
        }
        else
        {
            Redirect::toRoute('game/login');
        }
    }

    public function game(){
        if(Session::has('player'))
        {
            return View::make('game.game');
        }
        else
        {
            Redirect::toRoute('game/login');
        }
    }

    public function logout(){
        Session::destroy();
        Redirect::toRoute('game/login');
    }

    public function login(){
        return View::make('game.login');
    }

    public function authenticate(){
        if(Post::has('username') && Post::has('password')) {
            $username = addslashes(Post::get('username'));
            $password = addslashes(Post::get('password'));
            $password = hash("sha256", $password);

            $userfound = false;
            $i = 1;
            while(!$userfound && $i <= sizeof(User::all()))
            {
                try {
                    $user = User::find($i);
                    if($user->username == $username){
                        $userfound = true;
                    }
                }catch(Exception $ex){}
                $i++;
            }
            if(trim($user->username) == trim($username) && trim($user->password) == trim($password))
            {
                if(!$user->isbanned) {
                    $i = 1;
                    $gamesIndex = 0;
                    while($i <= sizeof(Game::all()))
                    {
                        try {
                            $game = Game::find($i);
                            if($game->idutilizador == $user->id){
                                $games[$gamesIndex] = $game;
                                $gamesIndex++;
                            }
                        }catch(Exception $ex){
                            echo  sizeof(Game::all());
                        }
                        $i++;
                    }
                    if(isset($games))
                    {
                        Session::set("games", $games);
                    }
                    Session::set("player", $user);
                    Redirect::toRoute('game/index');
                }
                else{
                    Redirect::toRoute('game/login');
                }
            }
            else{
                Redirect::toRoute('game/login');
            }
        }
    }

    public function createAccount(){
        $post = Post::getAll();
        if($post["password"] == $post["confirmPassword"]) {
            unset($post["confirmPassword"]);
            $post["password"] = hash("sha256", $post["password"]);
            $date = strtotime($post["dataNascimento"]);
            $date = date('Y/m/d', $date);
            $post["dataNascimento"] = (string)$date;

            $user = new User($post);
            if ($user->is_valid()) {
                $user->save();
                Redirect::toRoute('game/login');
            } else {
                // return form with data and errors
                Redirect::flashToRoute('game/register', ['user' => $user]);
            }
        }
        else{
            Redirect::flashToRoute('game/register', ['error' => 1]);
        }
    }

    public function register(){
        return View::make('game.register');
    }
}