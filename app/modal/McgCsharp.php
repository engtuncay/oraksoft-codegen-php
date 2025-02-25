<?php

namespace modal;

use Engtuncay\Phputils8\meta\FiColList;
use Engtuncay\Phputils8\meta\FkbList;
use ficols\McgFiCol;

class McgCsharp
{
    public static function actGenFiColListByExcel(FkbList $fkbExcel)
    {

        /**
         * @var FiColList
         */
        $fiCols = McgFiCol::getFiColListFromFkbList($fkbExcel);

        //if (FiCollection.isEmpty(fiCols)) return;


        /**
         * @var string
         */
        $templateFiColMethod = self::getTemplateFiColMethod();

//        $buffer[] = "Merhaba";
//        $buffer[] = " Dünya!";
        // Tüm parçaları tek seferde birleştir
        //$result = implode('', $buffer);

        //fiCol.buiColType(OzColType.{{fieldType}});
        $sbClassBody = []; //new StringBuilder();
        $sbFiColMethodsBody = []; //new StringBuilder();

        //int
        $index = 0;

        $sbFieldColsAddition = []; //new StringBuilder();
        $sbFieldColsAdditionTrans = []; //new StringBuilder();

//        for (FiCol fiCol : fiCols) {

        foreach ($fkbExcel->getItems() as $fkbCol) {

                //StringBuilder
                //$sbFiColMethodBody = genFiColMethodBodyDetailByFiCol($fkbCol);
//
//            FiKeyBean fkbParamsFiColMethod = new FiKeyBean();
//            String fieldName = fiCol . getOfcTxFieldName();
//            //fkbParamsFiColMethod.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
//            fkbParamsFiColMethod . add("fieldMethodName", fieldName);
//            fkbParamsFiColMethod . add("fieldName", fieldName);
//            fkbParamsFiColMethod . add("fieldHeader", fiCol . getOfcTxHeader());
//            fkbParamsFiColMethod . add("fiColMethodBody", sbFiColMethodBody . toString());
//            String txFiColMethod = FiString . substitutor(templateFiColMethod, fkbParamsFiColMethod);
//            sbFiColMethodsBody . append(txFiColMethod) . append("\n\n");
//
//            if (!FiBool . isTrue(fiCol . getOftBoTransient())) {
//                sbFieldColsAddition . append("\tfiColList.Add(") . append(fieldName) . append("());\n");
////                sbFieldColsAddition.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
//            } else {
//                sbFieldColsAdditionTrans . append("\tfiColList.Add(") . append(fieldName) . append("());\n");
////                sbFieldColsAdditionTrans.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
//            }
//
//index++;
//
        }
//}
//
//String tempGenTableCols = "public static FiColList GenTableCols() {\n\n" +
//                "\tFiColList fiColList = new FiColList();\n\n" +
//                "{{fiColsAddition}}\n" +
//                "\treturn fiColList;\n" +
//                "}";
//
//        String txGenTableColsMethod = FiTemplate . replaceParams(tempGenTableCols, FiKeyBean . bui() . putKeyTos("fiColsAddition", sbFieldColsAddition . toString()));
//        sbClassBody . append("\n") . append(txGenTableColsMethod) . append("\n");
//
//        String tempGenTableColsTrans = "public static FiColList GenTableColsTrans() {\n\n" +
//                "\tFiColList fiColList = new FiColList();\n\n" +
//                "{{fiColsAddition}}\n" +
//                "\treturn fiColList;\n" +
//                "}";
//
//        String txGenTableColsMethodTrans = FiTemplate . replaceParams(tempGenTableColsTrans
//                    , FiKeyBean . bui() . putKeyTos("fiColsAddition", sbFieldColsAdditionTrans . toString()));
//        sbClassBody . append("\n") . append(txGenTableColsMethodTrans) . append("\n");
//
//
//        sbClassBody . append("\n") . append(sbFiColMethodsBody . toString()) . append("\n");
//
//        String classPref = "FiCols";
//        //FIXME entity name çekilecek
//        String txEntityName = fiCols . get(0) . getOfcTxEntityName(); //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
//
//        FiKeyBean fkbParamsMain = new FiKeyBean();
//        fkbParamsMain . add("classPref", classPref);
//        fkbParamsMain . add("entityName", txEntityName);
//        fkbParamsMain . add("classBody", sbClassBody . toString());
//
//        String templateMain = getTemplateFiColsClassWithInterface();
//        String txResult = FiTemplate . replaceParams(templateMain, fkbParamsMain);
//
//        getOccHomeCont() . appendTextNewLine(txResult);

        //getGcgHome().appendTextNewLine(FiConsole.textFiCols(fiCols));

    }

    private static function getTemplateFiColMethod(): string
    {
        $txTemplate = "";

        return $txTemplate;
    }
}
