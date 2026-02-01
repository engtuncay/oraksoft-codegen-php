//import { FiKeybean } from "./libs/oraksoft-ui.js";
//const { greetOrakSoftUi } = require("oraksoft-ui");

function addOption(element, value,textContent) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = textContent;
    element.appendChild(option);
}

function addOptionsToElement(elementById, txIdSeciniz = "-1") {
  addOption(elementById, txIdSeciniz, "Seçiniz");
  addOption(elementById, "1", "FiCol Sınıf");
  addOption(elementById, "2", "FiMeta By DML Template");
  addOption(elementById, "3", "FkbCol Sınıf");
  addOption(elementById, "4", "FiMeta Sınıf");
}

let txIdSeciniz = "-1";

let eleSelCsharp = document.getElementById("selCsharp");
addOptionsToElement(eleSelCsharp);

let eleSelPhp = document.getElementById("selPhp");
addOptionsToElement(eleSelPhp);

let eleSelTs = document.getElementById("selTs");
addOptionsToElement(eleSelTs);

let eleSelJava = document.getElementById("selJava");
addOptionsToElement(eleSelJava);

let eleSelSql = document.getElementById("selSql");
addOption(eleSelSql,txIdSeciniz,"Seçiniz");
addOption(eleSelSql,"1","Sql Create Table");


//import { fiBsModal, testOrakSoftUi } from '../../orak_modules/oraksoft-ui/oraksoft-ui.js';
//fiBsModal(`<p>OrakSoft UI modülü başarıyla yüklendi!</p>`,"orakSoftUiModal",false);

//let fkb = new Fikeybean();
//import { Fdr } from './libs/oraksoft-ui.js';

//console.log(testOrakSoftUi("dünya"));
//let fiCol = new FiCol();
