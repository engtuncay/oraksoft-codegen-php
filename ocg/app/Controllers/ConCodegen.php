<?php

namespace App\Controllers;

use codegen\ficols\FicFiCol;
use codegen\modals\CgmFiColClass;
use codegen\modals\CgmFiColUtil;
use codegen\modals\DtoCodeGen;
use codegen\modals\ICogFicSpecs;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Excel\FiExcel;
use Engtuncay\Phputils8\FiCsv\FiCsv;
use Engtuncay\Phputils8\Meta\Fdr;
use Engtuncay\Phputils8\Meta\FkbList;

class ConCodegen extends BaseController
{
  public function index()
  {
    // POST verilerini işle (formdan veri gelirse)
    if ($this->request->getMethod() === 'post') {
      return $this->processCodegen();
    }
    $data = [];
    // GET isteği için codegen sayfasını göster
    return view('codegen', $data);
  }

  private function processCodegen()
  {
    $fdrData = new Fdr();

    // Form verilerini al
    $selCsharp = $this->request->getPost('selCsharp');
    $selTs = $this->request->getPost('selTs');
    $selPhp = $this->request->getPost('selPhp');
    $selJava = $this->request->getPost('selJava');

    $excelFile = $this->request->getFile('excelFile');

    $uploadedFile = $this->request->getFile('excelFile'); // $_FILES['excelFile'];

    // Dosya geçici olarak kaydediliyor
    $_SESSION["uploaded_file"] = $_FILES["excelFile"]["name"];

    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
      //die('Dosya yüklenirken hata oluştu.');
      $fdrData->setMessage("Dosya yüklenirken Hata oluştu.");
      //goto endExcelOkuma;
    }




    $data = [];
    // [[../Views/codegen.php]] 
    return view('codegen', $data);

    // Dosya yükleme işlemi


    // if ($excelFile && $excelFile->isValid() && !$excelFile->hasMoved()) {
    //     // Dosya işleme mantığı burada olacak
    //     $uploadPath = FCPATH . 'uploads/';
    //     $fileName = $excelFile->getRandomName();
    //     $excelFile->move($uploadPath, $fileName);

    //     // Code generation işlemi burada yapılacak
    //     // ...

    //     // Sonuç sayfasını göster veya JSON response döndür
    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Code generation completed',
    //         'file' => $fileName
    //     ]);
    // }

    // return $this->response->setJSON([
    //     'status' => 'error',
    //     'message' => 'File upload failed'
    // ]);
  }

  /**
 * @param array|string $fileExtension
 * @param mixed $inputFileName
 * @param FkbList $fkbListData
 * @param CogFicCsharpSpecs $IFiColClass
 * @param array $arrDtoCodeGen
 * @return array
 */
function generateDtoCodeFromFile(array|string $fileExtension, mixed $inputFileName, FkbList $fkbListData, ICogFicSpecs $IFiColClass, array $arrDtoCodeGen): array
{

  if ($fileExtension == "csv") {
    $fiCsv = new FiCsv();
    $fdrData = $fiCsv::read($inputFileName, FicFiCol::GenTableCols());
    $fkbListData = $fdrData->getFkbListInit();
  }

  if ($fileExtension == "xlsx" || $fileExtension == "xls") {
    $fiExcel = new FiExcel();
    $fdrData = $fiExcel::readExcelFile($inputFileName, FicFiCol::GenTableCols());
    $fkbListData = $fdrData->getFkbListInit();
  }

  //echo var_export($fkbListExcel, true);

  /** @var FkbList[] $arrFkbListExcel */
  $arrFkbListExcel = CgmFiColUtil::arrEntityFkbExcel($fkbListData);
  $txIdPref = "java";
  $lnForIndex = 0;

  foreach ($arrFkbListExcel as $fkbExcel) {
    $lnForIndex++;
    $dtoCodeGen = new DtoCodeGen();
    $sbTxCodeGen1 = new FiStrbui();
    $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
    $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbExcel, $IFiColClass));
    $sbTxCodeGen1->append("\n");
    $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
    $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
    $arrDtoCodeGen[] = $dtoCodeGen;
  }

  return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }
}
