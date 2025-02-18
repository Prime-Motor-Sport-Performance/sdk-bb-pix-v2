<?php

namespace PIXService;

use PIXService\Helpers\ResponseHelper;

class PIXService 
{
  private $accessToken;
  private $refreshToken;

  public $clienteId;
  public $clientSecret;
  public $apiKey; // "gw-dev-app-key"
  public $apiUrl = "https://api.hm.bb.com.br/pix/v2"; // Develop Env Default

  public static function make($apiKey, $clienteId, $clientSecret, $apiUrl) 
  {
    return new self($apiKey, $clienteId, $clientSecret, $apiUrl);
  }

  public function __construct($apiKey, $clienteId, $clientSecret)
  { 
      $this->apiKey = $apiKey;
      $this->clienteId = $clienteId;
      $this->clientSecret = $clientSecret;
  }

  /**
   * Criar novo PIX QRCode Dinâmico
   * Method: POST
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function create($txId = null) 
  {
    $code = 200;

    switch ($code) {
      
      case 201: 
        return ResponseHelper::success('Cobrança imediata criada.', []);
        break;
      case 400: 
        return ResponseHelper::success('Requisição com formato inválido.', []);
        break;
      case 403: 
        return ResponseHelper::success('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 503: 
        return ResponseHelper::success('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }

    
  }
  /**
   * Criar novo PIX QRCode Dinâmico com TXID
   * Method: POST
   * Endpoint: /cob ou Endpoint: /cob/{txid}
   */
  public function cirarComTxId($txId = null) 
  {
    $code = 200;

    switch ($code) {
      
      case 201: 
        return ResponseHelper::success('Cobrança imediata criada.', []);
        break;
      case 400: 
        return ResponseHelper::success('Requisição com formato inválido.', []);
        break;
      case 403: 
        return ResponseHelper::success('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 404: 
        return ResponseHelper::success('Recurso solicitado não foi encontrado.', []);
        break;
      case 503: 
        return ResponseHelper::success('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }

    
  }

  /**
   * Editar PIX QRCode Dinâmico
   * Method: POST
   * Endpoint: /cob
   */
  public function update()
  {
    $code = 200;

    switch ($code) {
      
      case 200: 
        return ResponseHelper::success('Lista de cobranças imediatas.', []);
        break;
      case 400: 
        return ResponseHelper::success('Requisição de participante autenticado que viola alguma regra de autorização.', []);
        break;
      case 503: 
        return ResponseHelper::success('Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.', []);
        break;
    }
  }

  /**
   * Excluir PIX QRCode Dinâmico
   * Method: POST
   * Endpoint: /cob
   */
  public function delete()
  {

  }

  /**
   * Consultar PIX
   * Method: GET
   * Endpoint: /cob
   */
  public function search(
      string $dataInicio, 
      string $dataFim, 
      string $cpf,
      string $cnpj,
      bool $locationPresente, 
      string $status, 
      int $paginaAtual = 0, 
      int $itensPorPagina = 100
    )
  {

  }


  /**
   * Consultar PIX por TXID
   */
  public function find($txId) 
  {

    return $this;
  }


  /**
   * Getters
   */
  public function getAccessToken() 
  {
    return $this->accessToken;
  }

  public function getRefreshToken() 
  {
    return $this->refreshToken;
  }

}