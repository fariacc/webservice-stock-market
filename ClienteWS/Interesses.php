<!-- 
    Author: fariacc
-->

<?php
    $SoapClient = new SoapClient("http://localhost:8080/StockMarketWS/StockMarketWS?wsdl");
    
    if (isset($_POST["consultarInteresses"])){    
        unset($_POST["consultarInteresses"]);

        $params = array(
            'clienteArg'=>$_POST["clienteArg"]
        );
        $resultConsultaInteresses = $SoapClient->consultarInteresses($params);
    }
    
    else if (isset($_POST["cadastrarInteresse"])){    
        unset($_POST["cadastrarInteresse"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"],
            'quantidadeArg'=>$_POST["quantidadeArg"],
            'limitePerdaArg'=>$_POST["limitePerdaArg"],
            'limiteGanhoArg'=>$_POST["limiteGanhoArg"]

        );
        $resultCadastroInteresse = $SoapClient->cadastrarInteresse($params);
    }
    
    else if (isset($_POST["removerInteresse"])){    
        unset($_POST["removerInteresse"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultRemocaoInteresse = $SoapClient->removerInteresse($params);
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">;
        <link rel="stylesheet" href="./css/main.css">
        <title>Interesses | Stock Market Application</title>
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
                        <a class="nav-link active" href="Carteira.php">Carteira</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="CompraVenda.php">Compra e venda</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="Interesses.php">Interesses</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="Cotacoes.php">Cotações</a>
                    </li> 
                </ul>
                
                <div class="row" id="aba-carteira">
                    <div class="col-md-4">
                        <ul class="nav nav-tabs flex-column" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="consultar-interesses-tab" data-toggle="tab" href="#consultar-interesses" role="tab" aria-controls="consultar-interesses" aria-selected="true">Consultar interesses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cadastrar-interesse-tab" data-toggle="tab" href="#cadastrar-interesse" role="tab" aria-controls="cadastrar-interesse" aria-selected="false">Cadastrar interesse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="remover-interesse-tab" data-toggle="tab" href="#remover-interesse" role="tab" aria-controls="remover-interesse" aria-selected="false">Remover interesse</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-8 tab-content">
                        <div id="consultar-interesses" class="tab-pane fade show active" role="tabpanel" aria-labelledby="consultar-interesses">
                            <div class="row">   
                                <form class="col-md-6" action="Interesses.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <button name="consultarInteresses" type="submit" class="btn btn-primary">Consultar interesses</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultConsultaInteresses)){
                                                $jsonObj = json_decode($resultConsultaInteresses->return);
                                                if (sizeof($jsonObj) != 0){
                                                    foreach ($jsonObj as $acao){
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Ação: ".$acao->codigo;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Quantidade: ".$acao->quantidade;
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Limite de perda: R$ ".$acao->limitePerda.",00";
                                                        echo "</p>";
                                                        echo "<p class='text-center texto-secundario'>";
                                                        echo "Limite de ganho: R$ ".$acao->limiteGanho.",00";
                                                        echo "</p>";
                                                        echo "<hr>";
                                                    }
                                                }
                                                else{
                                                    echo "<p class='text-center texto-principal'>";
                                                    echo "<span>Nenhum interesse encontrado</span>";
                                                    echo "</p>";
                                                }
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Consulte seus interesses</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="cadastrar-interesse" class="tab-pane fade" role="tabpanel" aria-labelledby="cadastrar-interesse">
                            <div class="row">
                                <form class="col-md-6" action="Interesses.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="quantidadeArg" type="text" class="form-control" placeholder="Quantidade desejada">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="limitePerdaArg" type="text" class="form-control" placeholder="Limite de perda">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="limiteGanhoArg" type="text" class="form-control" placeholder="Limite de ganho">
                                    </div> 
                                    <button name="cadastrarInteresse" type="submit" class="btn btn-primary">Cadastrar interesse</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php 
                                            if (isset($resultCadastroInteresse)){
                                                $jsonObj = json_decode($resultCadastroInteresse->return);
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Interesse cadastrado com sucesso!</span>";
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Ação: ".$jsonObj->codigo;
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Quantidade: ".$jsonObj->quantidade;
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Limite de perda: R$ ".$jsonObj->limitePerda.",00";
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Limite de ganho: R$ ".$jsonObj->limiteGanho.",00";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Cadastre interesse em uma ação</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="remover-interesse" class="tab-pane fade" role="tabpanel" aria-labelledby="remover-interesse">
                            <div class="row">   
                                <form class="col-md-6" action="Interesses.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="removerInteresse" type="submit" class="btn btn-primary">Remover interesse</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultRemocaoInteresse)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultRemocaoInteresse->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Remova um interesse em uma ação</span>";
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
