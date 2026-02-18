<?php

namespace Codegen\Modals;

use Codegen\FiRepos\RepoCodegen;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;

class CgmTableToCsv
{
  // 
  public static function getCodeByTable(string $txDbProfile, array $arrCliArgs): Fdr
  {
    // Burada tabloya göre CSV oluşturma işlemini gerçekleştirin
    // Örneğin, $txDbProfile ve $arrCliArgs parametrelerini kullanarak veritabanından veri çekip CSV'ye dönüştürebilirsiniz
    // İşlem sonucunu bir Fdr nesnesi olarak döndürün
    // Örnek olarak, basit bir Fdr nesnesi oluşturup döndürelim
    $fdrGetTableFields = new Fdr();

    if (empty($arrCliArgs['table'])) {
      $fdrGetTableFields->setBoResult(false);
      $fdrGetTableFields->setTxMessage("Tablo adı belirtilmedi. '--table' parametresini ekleyin.");
      return $fdrGetTableFields;
    }

    $repo = new RepoCodegen($txDbProfile);
    $fdrGetTableFields = $repo->getTableFields($arrCliArgs['table'] ?? '');

    $fdrCsv = new Fdr();
    $csvString = FiCsv::arrayToCsvString($fdrGetTableFields->getFkbValue()->getArr());

    if ($csvString !== false) {
      $fdrCsv->setBoResult(true);
      $fdrCsv->setTxValue($csvString);
      $fdrCsv->setTxMessage("CSV string başarıyla oluşturuldu.");
    } else {
      $fdrCsv->setBoResult(false);
      $fdrCsv->setTxMessage("CSV string oluşturulurken hata oluştu.");
    }

    return $fdrCsv;
  }
}
