<?php

namespace Codegen\FiRepos;

use Codegen\OcgConfigs\OcgLogger;
use Engtuncay\Phputils8\FiDbs\FiAbsRepoGeneric;
use Engtuncay\Phputils8\FiDbs\FiDbTypes;
use Engtuncay\Phputils8\FiDbs\FiQuery;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class RepoCodegen extends FiAbsRepoGeneric
{

  public function __construct(?string $connProfile = null)
  {
    parent::__construct($connProfile);
  }

  public function getTableFields(string $tableName, string $tablePrefix = ""): Fdr
  {
    // Veritabanından tablo alanlarını çekme işlemi
    $dbType = env("database.$this->connProfile.dbType") ?: FiDbTypes::MYSQL;

    if ($dbType === FiDbTypes::MYSQL) {
      $fdr = new Fdr();
      return $fdr;
    }

    if ($dbType === FiDbTypes::MSSQL) {
      OcgLogger::info("RepoCodeGen-getTableFields: mssql için tablo alanları çekiliyor: $tableName");
      
      //sq202503101637 v4
      $sql = "--sq202503101637 v4
Select @tableName                   fcTxEntityName
  , (@tablePrefix + C.COLUMN_NAME) fcTxFieldName
  , C.DATA_TYPE                  fcTxFieldType
  , ''                           fcTxHeader
  , @tablePrefix                 fcTxPrefix
  , CASE WHEN C.CHARACTER_MAXIMUM_LENGTH IS NULL THEN C.NUMERIC_PRECISION
    ELSE C.CHARACTER_MAXIMUM_LENGTH END fcLnLength
  --, C.NUMERIC_PRECISION          fcLnPrecision
  , C.NUMERIC_SCALE              fcLnScale
  , C.IS_NULLABLE                fcBoNullable
  , Case When Z.CONSTRAINT_NAME Is Null Then 0
    Else 1 End As              fcBoPriKey -- IsPartOfPrimaryKey
  , C.COLUMN_NAME                fcTxDbField
From INFORMATION_SCHEMA.COLUMNS As C
    Outer Apply (Select CCU.CONSTRAINT_NAME
      From INFORMATION_SCHEMA.TABLE_CONSTRAINTS As TC
              Join INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE As CCU
                  On CCU.CONSTRAINT_NAME = TC.CONSTRAINT_NAME
      Where TC.TABLE_SCHEMA = C.TABLE_SCHEMA
        And TC.TABLE_NAME = C.TABLE_NAME
        And TC.CONSTRAINT_TYPE = 'PRIMARY KEY'
        And CCU.COLUMN_NAME = C.COLUMN_NAME) As Z
Where C.TABLE_NAME = @tableName  
    ";

      $fkbParams = new FiKeybean();
      $fkbParams->put("tableName", $tableName);
      $fkbParams->put("tablePrefix", $tablePrefix);

      $fiQuery = new FiQuery($sql, $fkbParams);
      $fdr = $this->getDbHelper()->selectFkb($fiQuery);
      return $fdr;
    }

    $fdr = new Fdr();
    return $fdr;
  }
}
