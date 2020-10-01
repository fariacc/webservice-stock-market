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
}
