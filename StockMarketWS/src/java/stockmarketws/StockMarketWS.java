/**
    @author fariacc
**/

package stockmarketws;

import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

import javax.jws.WebService;
import javax.jws.WebMethod;
import javax.jws.WebParam;

//para transformar em JSON
import com.google.gson.Gson;

//instanciando o web service
@WebService(serviceName = "StockMarketWS")
public class StockMarketWS {
        
    List<Acao> acoes = new ArrayList<>(); //lista de acoes que o cliente possui
    List<Interesse> interesses = new ArrayList<>(); //lista de acoes que o cliente deseja ser notificado quando atingirem limites de ganho/perda
    List<Acao> cotacoes = new ArrayList<>(); //lista de acoes que o cliente precisa/deseja monitorar (pode ou não ter essas acoes em carteira)
    List<Acao> ordensVenda = new ArrayList<>();//lista de acoes que estao disponiveis para venda
    
    //WebMethod expoe o método para os clientes poderem consumir
    @WebMethod(operationName = "consultarCarteira")
    //@WebParam são os parametros enviados pelo cliente
    //Consulta todas os acoes cadastradas na carteira do cliente
    public String consultarCarteira(@WebParam(name = "clienteArg") String clienteArg) {
        //o Gson transforma em JSON a lista filtrada de ações
        String _acoesJSON = new Gson().toJson(
            acoes.stream().filter(acao -> acao.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        
        return _acoesJSON;
    }
    
    @WebMethod(operationName = "consultarCarteiraAcaoEspecifica")
    //Consulta uma acao especifica na carteira do cliente
    public String consultarCarteiraAcaoEspecifica(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        //percorre a lista da carteira de acoes e filtra a acao pelo codigo inserido pelo 
	//cliente, caso esse codigo seja igual ao da acao percorrida no momento
        String _acaoJSON = new Gson().toJson(acoes.stream()
            .filter(acao -> acao.codigo.equals(codigoArg))
            .filter(acao -> acao.cliente.equals(clienteArg))
            .collect(Collectors.toList())
        );
        
        return _acaoJSON;
    }
    
    @WebMethod(operationName = "cadastrarAcaoCarteira")
    //Cadastra uma acao na carteira do cliente
    public String cadastrarAcaoCarteira(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg, 
        @WebParam(name = "quantidadeArg") Long quantidadeArg, 
        @WebParam(name = "precoArg") Long precoArg
    ) {
        
        //cria uma nova instancia de Acao
        Acao _acao = new Acao();  
        _acao.cliente = clienteArg;
        _acao.codigo = codigoArg;
        _acao.quantidade = quantidadeArg;
        _acao.preco = precoArg;
        
        //adiciona a acao cadastrada na lista da carteira do cliente
        acoes.add(_acao);
        
        String _acaoJSON = new Gson().toJson(_acao);
        
        return _acaoJSON;
    }
    
    @WebMethod(operationName = "removerAcaoCarteira")
    //Remove uma acao da carteira do cliente
    public String removerAcaoCarteira(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        // Busca o acao a ser removida pelo seu codigo e vê se o cliente eh o 
        //mesmo cliente que possui a acao
        Acao _acao = acoes.stream()
            .filter(acao -> acao.codigo.equals(codigoArg))
            .filter(acao -> acao.cliente.equals(clienteArg))
            .findFirst().orElse(null);
        
        //caso nao encontre a ação na carteira
        if (_acao == null) {
            return "Ação não encontrada";
        }
              
        //remove a acao da lista
        acoes.remove(_acao);
        
        return "Ação removida com sucesso";
    }
    
    @WebMethod(operationName = "comprarAcao")
    //Compra uma acao
    public String comprarAcao(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg, 
        @WebParam(name = "quantidadeArg") Long quantidadeArg, 
        @WebParam(name = "precoArg") Long precoArg
    ) {	
        //percorre a lista de ordens de venda e ve se a acao que quer 
        //comprar existe nessa lista
        Acao acaoVendendo = ordensVenda.stream()
            .filter(ordem -> ordem.codigo.equals(codigoArg))
            .findFirst().orElse(null);

        //caso a acao nao exista na lista de ordens de venda
        if (acaoVendendo == null) {
            return "Ação não encontrada";
        }

        //caso a quantidade que deseja comprar seja maior do que a quantidade disponivel
        if (!quantidadeSuficienteAcao(acaoVendendo, quantidadeArg)) {
            return "Quantidade da ação desejada não disponível";
        }

        //caso o preco que o cliente deseja pagar seja menor que o preco da acao
        if (precoArg < acaoVendendo.preco) {
            return "O preco unitário é maior do que seu preço máximo a pagar";
        }
        
        Acao _acaoComprada = new Acao();
        _acaoComprada.cliente = clienteArg;
        _acaoComprada.codigo = codigoArg;
        _acaoComprada.quantidade = quantidadeArg;
        _acaoComprada.preco = precoArg;
        
        //adiciona na carteira a ação comprada
        acoes.add(_acaoComprada);

        //adiciona nas cotações a ação comprada
        cotacoes.add(_acaoComprada);
        
        //Desconta a quantidade da acao que foi comprada e atualiza com o valor que sobrou depois da compra
        acaoVendendo.quantidade = (acaoVendendo.quantidade - quantidadeArg);

        //Se quantidade disponivel da acao for igual a zero, remove a acao da lista de ordens de venda
        if (acaoVendendo.quantidade.equals(0L)) {
            ordensVenda.remove(acaoVendendo);
        }

        //atualiza o preco da acao que esta para venda com o valor 
        //pago quando o cliente comprou a acao
        acaoVendendo.preco = precoArg;

        //percorre a carteira do cliente e encontra a acao que foi colocada para venda
        Acao acaoCarteiraAtualizada = acoes.stream()
            .filter(acao -> acao.codigo.equals(acaoVendendo.codigo))
            .filter(acao -> acao.cliente.equals(acaoVendendo.cliente))
            .findFirst().orElse(null);
        
        //atualiza a quantidade disponivel da acao na carteira
        acaoCarteiraAtualizada.quantidade = (acaoCarteiraAtualizada.quantidade - acaoVendendo.quantidade) + (acaoVendendo.quantidade - quantidadeArg);

	//atualiza o preco da acao na carteira, com o preco que foi pago quando outro cliente comprou a acao
        acaoCarteiraAtualizada.preco = acaoVendendo.preco;

        //percorre as cotacoes de todos os clientes e encontra a acao que foi colocada para venda
        Acao acaoCotacaoAtualizada = cotacoes.stream()
            .filter(acao -> acao.codigo.equals(acaoVendendo.codigo))
            .findFirst().orElse(null);

        //atualiza a quantidade disponivel da acao na lista de cotacoes
        acaoCotacaoAtualizada.quantidade = acaoVendendo.quantidade;

        //atualiza o preco da acao na lista de cotacoes com o preco que foi pago quando outro cliente comprou a acao
        acaoCotacaoAtualizada.preco = precoArg;

        return quantidadeArg + " unidades da ação " + codigoArg + " foram compradas por R$" + precoArg + ",00 cada";
    }
    
    @WebMethod(operationName = "venderAcao")
    //coloca uma ação a venda
    public String venderAcao(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg, 
        @WebParam(name = "quantidadeArg") Long quantidadeArg, 
        @WebParam(name = "precoArg") Long precoArg
    ) {		
        //percorre a carteira do cliente e ve se a acao que quer colocar para venda existe nessa lista
        Acao acaoNaCarteira = acoes.stream()
            .filter(acao -> acao.codigo.equals(codigoArg))
            .filter(acao -> acao.cliente.equals(clienteArg))
            .findFirst().orElse(null);

        //caso o cliente queira colocar para venda uma acao que nao possui
        if (acaoNaCarteira == null) {
            return "Você não possui essa ação na sua carteira";
        }

        //caso o cliente queira colocar para venda mais acoes do que possui em sua carteira
        if (!quantidadeSuficienteAcao(acaoNaCarteira, quantidadeArg)) {
            return "Você não possui essa quantidade para vender";
        }
        
        Acao _acaoVenda = new Acao();  
        _acaoVenda.cliente = clienteArg;
        _acaoVenda.codigo = codigoArg;
        _acaoVenda.quantidade = quantidadeArg;
        _acaoVenda.preco = precoArg;

        //insere a acao na lista de ordens de venda
        ordensVenda.add(_acaoVenda);
        
        return quantidadeArg + " unidades da ação " + codigoArg + " foram colocadas a venda por R$" + precoArg + ",00 cada";
    }
    
    @WebMethod(operationName = "consultarInteresses")
    //Consulta os interesses do cliente
    public String consultarInteresses(@WebParam(name = "clienteArg") String clienteArg) {
        //Busca os interesses do cliente, filtrando pelo nome
        String _interessesJSON = new Gson().toJson(
            interesses.stream().filter(interesse -> interesse.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        return _interessesJSON;
    }
    
    //Cadastra interesse no evento
    @WebMethod(operationName = "cadastrarInteresse")
    public String cadastrarInteresse(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg, 
        @WebParam(name = "quantidadeArg") Long quantidadeArg, 
        @WebParam(name = "limitePerdaArg") Long limitePerdaArg,
        @WebParam(name = "limiteGanhoArg") Long limiteGanhoArg
    ) {
        Interesse _interesse = new Interesse();  
        _interesse.cliente = clienteArg;
        _interesse.codigo = codigoArg;
        _interesse.quantidade = quantidadeArg;
        _interesse.limitePerda = limitePerdaArg;
        _interesse.limiteGanho = limiteGanhoArg;

        //adiciona na lista de interesses o novo interesse cadastrado
        interesses.add(_interesse);
        
        String _interesseJSON = new Gson().toJson(_interesse);
        
        return _interesseJSON;
    }
    
    @WebMethod(operationName = "removerInteresse")
    //Remove o interesse no evento
    public String removerInteresse(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        //Busca o interesse do cliente a ser removido pelo codigo da acao
        Interesse _interesse = interesses.stream()
            .filter(interesse -> interesse.codigo.equals(codigoArg))
            .filter(interesse -> interesse.cliente.equals(clienteArg))
            .findFirst().orElse(null);
        
        //caso nao exista interesse para determinada acao
        if (_interesse == null) {
            return "Interesse não encontrado";
        }
              
        //remove o interesse em determinada acao
        interesses.remove(_interesse);
        
        return "Interesse removido com sucesso";
    }
    
    @WebMethod(operationName = "obterCotacoes")
    //Obtem cotacao de todas as acoes do cliente
    public String obterCotacoes(@WebParam(name = "clienteArg") String clienteArg) {
        String _cotacoesJSON = new Gson().toJson(
            cotacoes.stream().filter(cotacao -> cotacao.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        return _cotacoesJSON;
    }
    
    @WebMethod(operationName = "obterCotacaoAcaoEspecifica")
    //Obtem cotacao de uma acao especifica do cliente
    public String obterCotacaoAcaoEspecifica(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        String _cotacaoJSON = new Gson().toJson(cotacoes.stream()
            .filter(cotacao -> cotacao.codigo.equals(codigoArg))
            .filter(cotacao -> cotacao.cliente.equals(clienteArg))
            .collect(Collectors.toList())
        );
        
        return _cotacaoJSON;
    }
    
    @WebMethod(operationName = "cadastrarAcaoCotacoes")
    //Insere uma acao na lista de cotacoes do cliente
    public String cadastrarAcaoCotacoes(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        Acao cotacaoAtualizada = ordensVenda.stream()
            .filter(ordem -> ordem.codigo.equals(codigoArg))
            .findFirst().orElse(null);
        
        if (cotacaoAtualizada == null) {
            return "Não é possivel fazer operações de cotações dessa ação pois ela não está disponível no mercado";
        }
        
        cotacoes.add(cotacaoAtualizada);
        
        return "Cotação cadastrada com sucesso!";
    }
    
    @WebMethod(operationName = "removerCotacaoAcaoEspecifica")
    //Remove uma acao das cotacoes do cliente
    public String removerCotacaoAcaoEspecifica(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        //Busca o cotacao da acao a ser removida pelo seu codigo
        Acao _cotacaoRemover = cotacoes.stream()
            .filter(cotacao -> cotacao.codigo.equals(codigoArg))
            .filter(cotacao -> cotacao.cliente.equals(clienteArg))
            .findFirst().orElse(null);
        
        if (_cotacaoRemover == null) {
            return "Cotação não encontrada";
        }
              
        cotacoes.remove(_cotacaoRemover);
        
        return "Cotação removida com sucesso";
    }
    
    //Valida se uma acao tem quantidade suficiente disponivel
    private Boolean quantidadeSuficienteAcao(Acao acaoArg, Long quantidadeArg) {
        return acaoArg.quantidade.compareTo(quantidadeArg) >= 0;
    }
    
}
