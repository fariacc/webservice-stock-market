<!-- 
    Author: fariacc
-->

<?php
    $SoapClient = new SoapClient("http://localhost:8080/StockMarketWS/StockMarketWS?wsdl");
    
    if (isset($_POST["venderAcao"])){    
        unset($_POST["venderAcao"]);

        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"],
            'quantidadeArg'=>$_POST["quantidadeArg"],
            'precoArg'=>$_POST["precoArg"]
        );
        $resultVendaAcao = $SoapClient->venderAcao($params);
    }
    
    else if (isset($_POST["comprarAcao"])){    
        unset($_POST["comprarAcao"]);
        $params = array(
            'clienteArg'=>$_POST["clienteArg"],
            'codigoArg'=>$_POST["codigoArg"],
            'quantidadeArg'=>$_POST["quantidadeArg"],
            'precoArg'=>$_POST["precoArg"]

        );
        $resultCompraAcao = $SoapClient->comprarAcao($params);
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">;
        <link rel="stylesheet" href="./css/main.css">
        <title>Compra e Venda | Stock Market Application</title>
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
                        <a class="nav-link active" href="CompraVenda.php">Compra e venda</a>
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
                                <a class="nav-link active" id="comprar-acao-tab" data-toggle="tab" href="#comprar-acao" role="tab" aria-controls="comprar-acao" aria-selected="true">Comprar ação</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vender-acao-tab" data-toggle="tab" href="#vender-acao" role="tab" aria-controls="vender-acao" aria-selected="false">Vender ação</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-8 tab-content">
                        <div id="comprar-acao" class="tab-pane fade show active" role="tabpanel" aria-labelledby="comprar-acao">
                            <div class="row">   
                                <form class="col-md-6" action="CompraVenda.php" method="post">
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
                                        <input name="precoArg" type="text" class="form-control" placeholder="Preço a pagar">
                                    </div>  
                                    <button name="comprarAcao" type="submit" class="btn btn-primary">Comprar ação</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultCompraAcao)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultCompraAcao->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Compre uma ação</span>";
                                                echo "</p>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="vender-acao" class="tab-pane fade" role="tabpanel" aria-labelledby="vender-acao">
                            <div class="row">   
                                <form class="col-md-6" action="CompraVenda.php" method="post">
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
                                    <button name="venderAcao" type="submit" class="btn btn-primary">Vender ação</button>
                                </form>
                                <div class="col-md-6">
                                    <div class="resultados">
                                        <?php
                                            if (isset($resultVendaAcao)){
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>".$resultVendaAcao->return."</span>";
                                                echo "</p>";
                                            }
                                            else {
                                                echo "<p class='text-center texto-principal'>";
                                                echo "<span>Venda uma ação</span>";
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
