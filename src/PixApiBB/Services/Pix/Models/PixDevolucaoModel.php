<?php

namespace PixApiBB\Services\Pix\Models;

class PixDevolucaoModel
{
  public $id;
  public $rtrId;
  public $valor;
  public $horario;
  public $horarioSolicitacao;
  public $status;

  public const STATUS_EM_PROCESSAMENTO = "EM_PROCESSAMENTO";
  public const STATUS = [];
}
