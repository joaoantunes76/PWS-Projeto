<?php
use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Post;

class GameController extends BaseController
{
    private $DiceTrigger = false;
    private $PlayerScore = 0;
    private $CpuScore = 0;
    private $CpuTurn = false;

    public function index(){
        if(Session::has('player'))
        {
            $sql = "SELECT dataHora, resultado FROM games
                    WHERE idUtilizador = 1
                    ORDER BY id DESC
                    LIMIT 10;";
            $games = Game::find_by_sql($sql);
            if(!Session::has("primeiraJodada")){
                Session::set("primeiraJogada", true);
            }
            return View::make('game.index', ['games' => $games]);
        }
        else
        {
            Redirect::toRoute('game/login');
        }
        return null;
    }

    public function game(){
        if(Session::has('player'))
        {
            $this->DiceTrigger = $this->checkSelectedNums();
            if(Session::get("primeiraJogada")){
                $this->DiceTrigger = true;
                Session::set("primeiraJogada", false);
            }
            else {
                if (!$this->CpuTurn) {
                    $this->CpuTurn = $this->checkForCpuTurn();
                    if ($this->CpuTurn) {
                        $this->cpuPlay();
                    }
                }
            }
            Session::set("cpuTurn", $this->CpuTurn);
            Session::set("PlayerScore", $this->PlayerScore);
            Session::set("CpuScore", $this->CpuScore);
            Session::set("podeLancarDados", $this->DiceTrigger);
            return View::make('game.game', ['dados' => null]);
        }
        else
        {
            Redirect::toRoute('game/login');
        }
        return null;
    }

    function cpuPlay(){
        $availableNums = [1,2,3,4,5,6,7,8,9];
        $cpuPlays = array();
        $availablePlays = 20;
        while($this->CpuScore == 0 && $availablePlays > 0) {
            $availablePlays--;
            $this->DiceTrigger = true;
            Session::set("podeLancarDados", true);
            $dados = $this->lancarDados();
            $sumDados = $dados[0] + $dados[1];
            if(in_array($sumDados, $availableNums)){
                if ($key = array_search($sumDados, $availableNums)) {
                    unset($availableNums[$key]);
                }
            }
            else {
                foreach ($availableNums as $num1) {
                    foreach ($availableNums as $num2) {
                        $result = $num1 + $num2;
                        if ($result == $dados && $num1 != $num2) {
                            if ($key = array_search($num1, $availableNums)) {
                                $cpuPlays[$availablePlays[$key]];
                                unset($availableNums[$key]);
                            }
                            if($key = array_search($num2, $availableNums)) {
                                $cpuPlays[$availablePlays[$key]];
                                unset($availableNums[$key]);
                            }
                        }
                    }
                }
            }
            $this->checkPossiblePlay($availableNums, $sumDados);
            Session::set("cpuPlays", $cpuPlays);
        }
        if($this->PlayerScore > $this->CpuScore){
            $this->playerWins();
        }
        else{
            $this->giveup();
        }
    }

    public function edit(){
        return View::make('game.edit');
    }

    public function confirmEdit(){
        if(Session::has("player")) {
            $post = Post::getAll();
            $userSession = Session::get("player");
            $user = User::find($userSession->id);
            $user->nomecompleto = $post["nomecompleto"];
            $user->email = $post["email"];
            $user->datanascimento = (string)$post["datanascimento"];
            $user->password = hash("sha256", $post["password"]);
            if($user->is_valid()){
                $user->save();
            }
            Redirect::toRoute('game/logout');
        }
        Redirect::toRoute('game/login');
    }

    public function playerWins(){
        date_default_timezone_set("Europe/Lisbon");
        if(Session::has('player')) {
            $user = Session::get("player");
            $date = date('Y-m-d H:i:s', time());
            $game = new Game;
            $game->resultado = "victory";
            $game->datahora = $date;
            $game->idutilizador = $user->id;
            if($game->is_valid()){
                $game->save();
            }
            echo '<h1>Victory</h1>';
        }
    }

    public function newGame(){
        if(Session::has('player'))
        {
            $this->PlayerScore = 0;
            $this->CpuScore = 0;
            if(Session::has("selectedNums"))
                Session::remove("selectedNums");
            if(Session::has("playerNums"))
                Session::remove("playerNums");
            $this->DiceTrigger = true;
            Redirect::toRoute('game/game');
        }
        else
        {
            Redirect::toRoute('game/login');
        }
        return null;
    }

    public function select($num){
        if(Session::has("selectedNums")){
            $selectedNums = array();
            $selectedNums = Session::get("selectedNums");
            if(!in_array($num, $selectedNums)){
                $selectedNums[sizeof($selectedNums)] = $num;
            }
            else{
                if(sizeof($selectedNums) < 2){
                    $selectedNums = null;
                    Session::remove("selectedNums");
                    Redirect::toRoute('game/game');
                }
                else {
                    if ($key = array_search($num, $selectedNums)) {
                        unset($selectedNums[$key]);
                    }
                }
            }
        }
        else{
            $selectedNums = array();
            $selectedNums[0] = $num;
        }
        if($selectedNums != null) {
            Session::set("selectedNums", $selectedNums);
        }
        Redirect::toRoute('game/game');
    }

    public function checkSelectedNums()
    {
        if(Session::has("selectedNums")) {
            $selectedNums = Session::get("selectedNums");
            $sum = 0;
            foreach($selectedNums as $selectedNum){
                $sum += $selectedNum;
            }
            $dados = Session::get("dados");
            if($sum == ($dados[0] + $dados[1])){
                return true;
            }
        }
        else{
           return false;
        }
    }

    public function lancarDados(){
        if(Session::has("selectedNums")){
            $selectedNums = Session::get("selectedNums");
            if(Session::has("playerNums")) {
                $playerNums = Session::get("playerNums");
                $playerNums = array_merge($playerNums,$selectedNums);
            }
            else{
                $playerNums = $selectedNums;
            }
            Session::set("playerNums", $playerNums);
            Session::remove("selectedNums");
        }
        if(Session::get("podeLancarDados")) {
            $dados[0] = rand(1, 6);
            $dados[1] = rand(1, 6);

            if(Session::has("playerNums")){
                $playerNums = Session::get("playerNums");
                $dadosJuntos = $dados[0] + $dados[1];
                $availableNums = [1,2,3,4,5,6,7,8,9];
                foreach($playerNums as $playerNum){
                    if(in_array($playerNum, $availableNums)){
                        unset($availableNums[$playerNum-1]);
                    }
                }
                $keepPlaying = $this->checkPossiblePlay($availableNums, $dadosJuntos);
                if(!$keepPlaying){
                    Session::set("cpuTurn", true);
                }
            }
            Session::set("dados", $dados);
            $this->DiceTrigger = false;
            if(!$this->CpuTurn) {
                Redirect::toRoute('game/game');
            }
            else{
                return $dados;
            }
        }
    }

    public function checkForCpuTurn(){
        if(Session::has("playerNums")) {
            $playerNums = Session::get("playerNums");
            $dados = Session::get("dados");
            $availableNums = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            foreach ($playerNums as $playerNum) {
                if (in_array($playerNum, $availableNums)) {
                    unset($availableNums[$playerNum - 1]);
                }
            }
            $result = !$this->checkPossiblePlay($availableNums, $dados);
            echo $result ? 'true':'false';
            return $result;
        }
    }

    public function checkPossiblePlay($availableNums, $dados){
        foreach($availableNums as $num1){
            foreach($availableNums as $num2){
                $result = $num1 + $num2;
                if($result == $dados && $num1 != $num2){
                    return true;
                }
            }
        }
        $sum = 0;
        foreach ($availableNums as $num){
            $sum += $num;
        }
        if(!$this->CpuTurn){
            $this->PlayerScore = $sum;
        }
        else {
            $this->CpuScore = $sum;
        }
        return false;
    }

    public function giveup(){
        date_default_timezone_set("Europe/Lisbon");
        if(Session::has('player')) {
            $this->PlayerScore = 0;
            $this->CpuScore = 0;
            Session::remove("dados");
            Session::remove("selectedNums");
            Session::remove("playerNums");
            $this->DiceTrigger = true;
            $user = Session::get("player");
            $date = date('Y-m-d H:i:s', time());
            $game = new Game;
            $game->resultado = "defeat";
            $game->datahora = $date;
            $game->idutilizador = $user->id;
            if($game->is_valid()){
                $game->save();
            }
            Redirect::toRoute('game/index');
        }
    }

    public function top10(){
        $sql = "SELECT username, count(games.id) as 'victories'
                FROM users
                    INNER JOIN games ON users.id = games.idUtilizador
                WHERE resultado LIKE 'victory'
                GROUP BY username
                ORDER BY victories DESC;";
        $games = Game::find_by_sql($sql);
        return View::make('game.top10', ['games' => $games]);
    }

    public function backoffice(){
        if(Session::has('player'))
        {
            $player = Session::get('player');
            if($player->isadmin) {
                $players = User::all();
                return View::make('game.backoffice', ['players' => $players]);
            }
            else{
                return View::make('game.index');
            }
        }
        else
        {
            Redirect::toRoute('game/login');
        }
    }

    public function changeBanStatus($id){
        $player = User::find($id);
        $player->isbanned = !$player->isbanned;
        $player->save();
        Redirect::toRoute('game/backoffice');
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