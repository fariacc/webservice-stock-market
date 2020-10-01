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

import com.google.gson.Gson;

@WebService(serviceName = "StockMarketWS")
public class StockMarketWS {
    List<Acao> acoes = new ArrayList<>(); //lista de acoes que o cliente possui
    List<Interesse> interesses = new ArrayList<>(); //lista de acoes que o cliente deseja ser notificado quando atingirem limites de ganho/perda
    List<Acao> cotacoes = new ArrayList<>(); //lista de acoes que o cliente precisa/deseja monitorar (pode ou não ter essas acoes em carteira)
    List<Acao> ordensCompra = new ArrayList<>();//lista de acoes que estao disponiveis para compra
    List<Acao> ordensVenda = new ArrayList<>();//lista de acoes que estao disponiveis para venda
    
    @WebMethod(operationName = "consultarCarteira")
    public String consultarCarteira(@WebParam(name = "clienteArg") String clienteArg) {
        String _acoesJSON = new Gson().toJson(
            acoes.stream().filter(acao -> acao.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        return _acoesJSON;
    }
    
    @WebMethod(operationName = "consultarCarteiraAcaoEspecifica")
    public String consultarCarteiraAcaoEspecifica(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        String _acaoJSON = new Gson().toJson(acoes.stream()
            .filter(acao -> acao.codigo.equals(codigoArg))
            .filter(acao -> acao.cliente.equals(clienteArg))
            .collect(Collectors.toList())
        );
        
        return _acaoJSON;
    }
    
    @WebMethod(operationName = "cadastrarAcaoCarteira")
    public String cadastrarAcaoCarteira(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg, 
        @WebParam(name = "quantidadeArg") Long quantidadeArg, 
        @WebParam(name = "precoArg") Long precoArg
    ) {
        Acao _acao = new Acao();  
        _acao.cliente = clienteArg;
        _acao.codigo = codigoArg;
        _acao.quantidade = quantidadeArg;
        _acao.preco = precoArg;
        
        acoes.add(_acao);
        
        String _acaoJSON = new Gson().toJson(_acao);
        
        return _acaoJSON;
    }
    
    @WebMethod(operationName = "removerAcaoCarteira")
    public String removerAcaoCarteira(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        Acao _acao = acoes.stream()
            .filter(acao -> acao.codigo.equals(codigoArg))
            .filter(acao -> acao.cliente.equals(clienteArg))
            .findFirst().orElse(null);
        
        if (_acao == null) {
            return "Ação não encontrada";
        }
              
        acoes.remove(_acao);
        
        return "Ação removida com sucesso";
    }
    
    @WebMethod(operationName = "consultarInteresses")
    public String consultarInteresses(@WebParam(name = "clienteArg") String clienteArg) {
        String _interessesJSON = new Gson().toJson(
            interesses.stream().filter(interesse -> interesse.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        return _interessesJSON;
    }
    
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
        
        interesses.add(_interesse);
        
        String _interesseJSON = new Gson().toJson(_interesse);
        
        return _interesseJSON;
    }
    
    @WebMethod(operationName = "removerInteresse")
    public String removerInteresse(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
        Interesse _interesse = interesses.stream()
            .filter(interesse -> interesse.codigo.equals(codigoArg))
            .filter(interesse -> interesse.cliente.equals(clienteArg))
            .findFirst().orElse(null);
        
        if (_interesse == null) {
            return "Interesse não encontrado";
        }
              
        interesses.remove(_interesse);
        
        return "Interesse removido com sucesso";
    }
    
    @WebMethod(operationName = "obterCotacoes")
    public String obterCotacoes(@WebParam(name = "clienteArg") String clienteArg) {
        String _cotacoesJSON = new Gson().toJson(
            cotacoes.stream().filter(cotacao -> cotacao.cliente.equals(clienteArg)).collect(Collectors.toList())
        );
        return _cotacoesJSON;
    }
    
    @WebMethod(operationName = "obterCotacaoAcaoEspecifica")
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
    public String removerCotacaoAcaoEspecifica(
        @WebParam(name = "clienteArg") String clienteArg, 
        @WebParam(name = "codigoArg") String codigoArg
    ) {
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
}
