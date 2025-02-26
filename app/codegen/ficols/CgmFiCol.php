<?php

namespace codegen\ficols;

use Engtuncay\Phputils8\meta\FiCol;
use Engtuncay\Phputils8\meta\FiColList;
use Engtuncay\Phputils8\meta\FiKeybean;
use Engtuncay\Phputils8\meta\FkbList;

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

            $txFieldName = $fkbItem->getValueByFiCol(FicMeta::ofcTxFieldName());
            $ficol->ofcTxFieldName = $txFieldName;

            $txHeader = $fkbItem->getValueByFiCol(FicMeta::ofcTxHeader());
            $ficol->ofcTxHeader = $txHeader;

            $ficols->add($ficol);
        }

        return $ficols;
    }
}