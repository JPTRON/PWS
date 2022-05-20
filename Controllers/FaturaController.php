<?php
require_once("./models/Auth.php");
require_once("./controllers/BaseController.php");

Class FaturaController extends Base
{
    public function registerFatura()
    {
        $auth = new Auth();

        $role = Utilizador::getUserRole($_SESSION["username"], $_SESSION["password"]);

        if($role != "F" && $role != "A") { $this->redirectToRoute(ROTA_LOGIN); }
        if(!isset($_POST["cliente"]) || !isset($_POST["produto"]) || !isset($_POST["quantidade"]) || !isset($_POST["total"]) || !isset($_POST["totalIva"])) { $this->redirectToRoute(ROTA_LOGIN); }

        //echo "<script>console.log(".json_encode($_POST["cliente"]).",".json_encode($_POST["produto"]).",". json_encode($_POST["quantidade"]).")</script>";
        
        $idFatura = Fatura::createFatura($_POST["cliente"], $_POST["total"], $_POST["totalIva"]);

        for($i = 0; $i < count($_POST["produto"]); $i++) 
        {
            $produto = Produto::getProduto($_POST["produto"][$i]);
            $totalLinha = $produto->preco * $_POST["quantidade"][$i];

            LinhaFatura::createLinha($idFatura, $_POST["produto"][$i], $produto->Iva_id, $_POST["cliente"], $_POST["quantidade"][$i], $totalLinha);
        }
    }
}