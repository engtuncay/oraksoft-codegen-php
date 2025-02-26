<?php

namespace codegen\ficols;

use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiColList;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;

class CgmFiCol
{

    public static function getFiColListFromFkbList(FkbList $fkbList):FiColList
    {
        $ficols = new FiColList();

        /**
         * @var FiKeybean $fkbItem
         */
        foreach ($fkbList->getItems() as $fkbItem) {

            $ficol = new FiCol();

            $txFieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
            $ficol->ofcTxFieldName = $txFieldName;

            $txHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
            $ficol->ofcTxHeader = $txHeader;

            $ficols->add($ficol);
        }

        return $ficols;
    }
}