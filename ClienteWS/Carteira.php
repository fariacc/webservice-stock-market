<!-- 
    Author: fariacc
-->

<?php
    //conexão com o endereço do WSDL que é o nosso Web Service
    $SoapClient = new SoapClient("http://localhost:8080/StockMarketWS/StockMarketWS?wsdl");
    
    //caso seja um POST para consultar a carteira de ações
    if (isset($_POST["consultarCarteira"])){   
        unset($_POST["consultarCarteira"]);

        //parametros que serão enviados ao método no Web Service
        $params = array(
            'clienteArg'=>$_POST["clienteArg"]
        );
        //recebendo o resultado do método que está no Web Service
        $resultConsultaCarteira = $SoapClient->consultarCarteira($params);
    }
    
    //caso seja um POST para consultar uma ação específica na carteira de ações
    else if (isset($_POST["consultarCarteiraAcaoEspecifica"])){    
        unset($_POST["consultarCarteiraAcaoEspecifica"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultConsultaCarteiraAcaoEspecifica = $SoapClient->consultarCarteiraAcaoEspecifica($params);
    }
    
    //caso seja um POST para cadastrar uma nova ação na carteira de ações
    else if (isset($_POST["cadastrarAcaoCarteira"])){    
        unset($_POST["cadastrarAcaoCarteira"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"],
            'quantidadeArg'=>$_POST["quantidadeArg"],
            'precoArg'=>$_POST["precoArg"]
        );
        $resultCadastroAcaoCarteira = $SoapClient->cadastrarAcaoCarteira($params);
    }
    
    //caso seja um POST para remover uma ação da carteira de ações
    else if (isset($_POST["removerAcaoCarteira"])){    
        unset($_POST["removerAcaoCarteira"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"]
        );
        $resultRemocaoAcaoCarteira = $SoapClient->removerAcaoCarteira($params);
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">;
        <link rel="stylesheet" href="./css/main.css">
        <title>Carteira | Stock Market Application</title>
    </head>
  <body>
      <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto text-center"> 
                <h2 class="text-light text-center">Stock Market Application</h2>
                <hr>
            </div>
            <div class="menu-tabs col-md-10 mx-auto text-center box-shadow">  
                <!--para navegar entre as páginas-->
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
                        <!--para navegar entre as opções de carteira-->
                        <ul class="nav nav-tabs flex-column" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="consultar-carteira-tab" data-toggle="tab" href="#consultar-carteira" role="tab" aria-controls="consultar-carteira" aria-selected="true">Consultar carteira</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="consultar-carteira-acao-especifica-tab" data-toggle="tab" href="#consultar-carteira-acao-especifica" role="tab" aria-controls="consultar-carteira-acao-especifica" aria-selected="false">Consultar ação específica</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cadastrar-acao-carteira-tab" data-toggle="tab" href="#cadastrar-acao-carteira" role="tab" aria-controls="cadastrar-acao-carteira" aria-selected="false">Cadastrar ação</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="remover-acao-tab" data-toggle="tab" href="#remover-acao" role="tab" aria-controls="remover-acao" aria-selected="false">Remover ação</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-8 tab-content">
                        <div id="consultar-carteira" class="tab-pane fade show active" role="tabpanel" aria-labelledby="consultar-carteira">
                            <div class="row">   
                                <!--formulário de consultar a carteira-->
                                <form class="col-md-6" action="Carteira.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <!--o name do botão define qual post será feito-->
                                    <button name="consultarCarteira" type="submit" class="btn btn-primary">Consultar carteira</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <!--resultado da consulta ao Web Service-->
                                        <?php
                                            if (isset($resultConsultaCarteira)){
                                                //recebendo o retorno do método do Web Service como um objeto JSON
                                                $jsonObj = json_decode($resultConsultaCarteira->return);
                                                //checando se existe algo no objeto
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
                                                //caso o objeto esteja vazio
                                                else{
                                                    echo "<p class='text-center texto-principal'>";
                                                    echo "<span>Nenhuma ação encontrada</span>";
                                                    echo "</p>";
                                                }
                                            }
                                            //inicial da página
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Consulte a sua carteira</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="consultar-carteira-acao-especifica" class="tab-pane fade" role="tabpanel" aria-labelledby="consultar-carteira-acao-especifica">
                            <div class="row">   
                                <!--formulário para consultar ação especifica na carteira-->
                                <form class="col-md-6" action="Carteira.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="consultarCarteiraAcaoEspecifica" type="submit" class="btn btn-primary">Consultar ação específica</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultConsultaCarteiraAcaoEspecifica)){
                                                //recebendo o retorno do método do Web Service como um objeto JSON
                                                $jsonObj = json_decode($resultConsultaCarteiraAcaoEspecifica->return);
                                                if (sizeof($jsonObj) != 0){
                                                    //percorrendo o objeto JSON
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
                                                //caso nenhuma ação tenha sido encontrada
                                                else{
                                                    echo "<p class='text-center texto-principal'>";
                                                    echo "<span>Nenhuma ação encontrada</span>";
                                                    echo "</p>";
                                                }
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Consulte uma ação específica na sua carteira</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="cadastrar-acao-carteira" class="tab-pane fade" role="tabpanel" aria-labelledby="cadastrar-acao-carteira">
                            <div class="row">
                                <!--formulário para cadastrar uma ação na carteira-->
                                <form class="col-md-6" action="Carteira.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="quantidadeArg" type="text" class="form-control" placeholder="Quantidade">
                                    </div>  
                                    <div class="form-group">                  
                                        <input name="precoArg" type="text" class="form-control" placeholder="Preço unitário">
                                    </div>  
                                    <button name="cadastrarAcaoCarteira" type="submit" class="btn btn-primary">Cadastrar na carteira</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php 
                                            if (isset($resultCadastroAcaoCarteira)){
                                                //recebendo o retorno do método do Web Service como um objeto JSON
                                                $jsonObj = json_decode($resultCadastroAcaoCarteira->return);
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Ação cadastrada com sucesso!</span>";
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Ação: ".$jsonObj->codigo;
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Quantidade: ".$jsonObj->quantidade;
                                                echo "</p>";
                                                echo "<p class='text-center texto-secundario'>";
                                                echo "Preço unitário: R$ ".$jsonObj->preco.",00";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Cadastre uma ação na carteira</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="remover-acao" class="tab-pane fade" role="tabpanel" aria-labelledby="remover-acao">
                            <div class="row">  
                                <!--formulário de remover a ação da carteira-->
                                <form class="col-md-6" action="Carteira.php" method="post">
                                    <div class="form-group">                  
                                        <input name="clienteArg" type="text" class="form-control" placeholder="Seu nome">
                                    </div>
                                    <div class="form-group">                  
                                        <input name="codigoArg" type="text" class="form-control" placeholder="Código da ação">
                                    </div>
                                    <button name="removerAcaoCarteira" type="submit" class="btn btn-primary">Remover ação</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            //checa se tem conteúdo na variável
                                            if (isset($resultRemocaoAcaoCarteira)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultRemocaoAcaoCarteira->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Remova uma ação específica na sua carteira</span>";
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
    <script src="./js/main.js"></script>
  </body>
</html>
