<!-- 
    Author: fariacc
-->

<?php
    $SoapClient = new SoapClient("http://localhost:8080/StockMarketWS/StockMarketWS?wsdl");
    
    if (isset($_POST["obterCotacoes"])){    
        unset($_POST["obterCotacoes"]);

        $params = array(
            'clienteArg'=>$_POST["clienteArg"]
        );
        $resultObterCotacoes = $SoapClient->obterCotacoes($params);
    }
    
    else if (isset($_POST["obterCotacaoAcaoEspecifica"])){    
        unset($_POST["obterCotacaoAcaoEspecifica"]);
        $params = array(
//            'clienteArg'=>'http://localhost:'.$_SERVER['REMOTE_PORT'],
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultObterCotacaoAcaoEspecifica = $SoapClient->obterCotacaoAcaoEspecifica($params);
    }
    
    else if (isset($_POST["cadastrarAcaoCotacoes"])){    
        unset($_POST["cadastrarAcaoCotacoes"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultCadastroAcaoCotacoes = $SoapClient->cadastrarAcaoCotacoes($params);
    }
    
    else if (isset($_POST["removerCotacaoAcaoEspecifica"])){    
        unset($_POST["removerCotacaoAcaoEspecifica"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultRemocaoCotacaoAcaoEspecifica = $SoapClient->removerCotacaoAcaoEspecifica($params);
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">;
        <link rel="stylesheet" href="./css/main.css">
        <title>Cotações | Stock Market Application</title>
    </head>
  <body>
      <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto text-center"> 
                <h2 class="text-light text-center">Stock Market Application</h2>
                <hr>
            </div>
            <div class="menu-tabs col-md-10 mx-auto text-center box-shadow">  
                <ul class="nav nav-tabs menu-superior">
                    <li class="nav-item">
                        <a class="nav-link" href="Carteira.php">Carteira</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="CompraVenda.php">Compra e venda</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="Interesses.php">Interesses</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link active" href="Cotacoes.php">Cotações</a>
                    </li> 
                </ul>
                
                <div class="row" id="aba-carteira">
                    <div class="col-md-4">
                        <ul class="nav nav-tabs flex-column" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="obter-cotacoes-tab" data-toggle="tab" href="#obter-cotacoes" role="tab" aria-controls="obter-cotacoes" aria-selected="true">Obter cotações</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="obter-cotacao-acao-especifica-tab" data-toggle="tab" href="#obter-cotacao-acao-especifica" role="tab" aria-controls="obter-cotacao-acao-especifica" aria-selected="false">Obter cotação específica</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cadastrar-cotacao-tab" data-toggle="tab" href="#cadastrar-cotacao" role="tab" aria-controls="cadastrar-cotacao" aria-selected="false">Cadastrar cotação</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="remover-cotacao-tab" data-toggle="tab" href="#remover-cotacao" role="tab" aria-controls="remover-cotacao" aria-selected="false">Remover cotação</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-8 tab-content">
                        <div id="obter-cotacoes" class="tab-pane fade show active" role="tabpanel" aria-labelledby="obter-cotacoes">
                            <div class="row">   
                                <form class="col-md-6" action="Cotacoes.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <button name="obterCotacoes" type="submit" class="btn btn-primary">Obter cotações</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultObterCotacoes)){
                                                $jsonObj = json_decode($resultObterCotacoes->return);
                                                if (sizeof($jsonObj) != 0){
                                                    foreach ($jsonObj as $acao){
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Ação: ".$acao->codigo;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Quantidade: ".$acao->quantidade;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Preço unitário: R$ ".$acao->preco.",00";
                                                        echo "</p>";
                                                        echo "<hr>";
                                                    }
                                                }
                                                else{
                                                    echo "<p class='text-center texto-principal'>";
                                                    echo "<span>Nenhuma cotação encontrada</span>";
                                                    echo "</p>";
                                                }
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Obtenha cotações</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="obter-cotacao-acao-especifica" class="tab-pane fade" role="tabpanel" aria-labelledby="obter-cotacao-acao-especifica">
                            <div class="row">   
                                <form class="col-md-6" action="Cotacoes.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="obterCotacaoAcaoEspecifica" type="submit" class="btn btn-primary">Obter cotação específica</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultObterCotacaoAcaoEspecifica)){
                                                $jsonObj = json_decode($resultObterCotacaoAcaoEspecifica->return);
                                                if (sizeof($jsonObj) != 0){
                                                    foreach ($jsonObj as $acao){
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Ação: ".$acao->codigo;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Quantidade: ".$acao->quantidade;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Preço unitário: R$ ".$acao->preco.",00";
                                                        echo "</p>";
                                                        echo "<hr>";
                                                    }
                                                }
                                                else{
                                                    echo "<p class='text-center texto-principal'>";
                                                    echo "<span>Nenhuma cotação encontrada</span>";
                                                    echo "</p>";
                                                }
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Obtenha a cotação de uma ação específica</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="cadastrar-cotacao" class="tab-pane fade" role="tabpanel" aria-labelledby="cadastrar-cotacao">
                            <div class="row">
                                <form class="col-md-6" action="Cotacoes.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="cadastrarAcaoCotacoes" type="submit" class="btn btn-primary">Cadastrar cotação</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php 
                                            if (isset($resultCadastroAcaoCotacoes)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultCadastroAcaoCotacoes->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Cadastre a cotação de uma ação específica</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="remover-cotacao" class="tab-pane fade" role="tabpanel" aria-labelledby="remover-cotacao">
                            <div class="row">   
                                <form class="col-md-6" action="Cotacoes.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="removerCotacaoAcaoEspecifica" type="submit" class="btn btn-primary">Remover cotação</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultRemocaoCotacaoAcaoEspecifica)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultRemocaoCotacaoAcaoEspecifica->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Remova a cotação de uma ação específica</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </body>
</html>
