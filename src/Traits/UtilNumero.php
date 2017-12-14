<?php

namespace App\Traits;

/**
 * Class UtilNumero
 *
 * @package \App\Traits
 */
trait UtilNumero
{

    /**
     * @param $valor
     *
     * @return float
     */
    public function trataFloat($valor)
    {
        return floatval(str_replace(',', '.', $valor));
    }
}
