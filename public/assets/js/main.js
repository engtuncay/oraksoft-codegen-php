//import { FiKeybean } from "./libs/oraksoft-ui.js";

//const { greetOrakSoftUi } = require("oraksoft-ui");

let elementById = document.getElementById("selCsharp");

function addOption(element, value,textContent) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = textContent;
    element.appendChild(option);
}

let txIdSeciniz = "-1";

addOption(elementById,txIdSeciniz,"Seçiniz");
addOption(elementById,"1","FiCol Csharp Sınıf");
addOption(elementById,"2","FiMeta By FiCol Template");

let eleSelPhp = document.getElementById("selPhp");

addOption(eleSelPhp,txIdSeciniz,"Seçiniz");
addOption(eleSelPhp,"1","FiCol Php Sınıf");
addOption(eleSelPhp,"2","FiMeta Php Sınıf");
addOption(eleSelPhp,"3","FkbCol Php Sınıf"); 
addOption(eleSelPhp,"4","FiMeta By FiCol Template");

let eleSelTs = document.getElementById("selTs");

addOption(eleSelTs,txIdSeciniz,"Seçiniz");
addOption(eleSelTs,"1","FiCol Ts Sınıf");

let eleSelJava = document.getElementById("selJava");

addOption(eleSelJava,txIdSeciniz,"Seçiniz");
addOption(eleSelJava,"1","FiCol Java Sınıf");


let eleSelSql = document.getElementById("selSql");

addOption(eleSelSql,txIdSeciniz,"Seçiniz");
addOption(eleSelSql,"1","Sql Create Table");

import { fiBsModal, testOrakSoftUi } from '../../orak_modules/oraksoft-ui/oraksoft-ui.js';

//fiBsModal(`<p>OrakSoft UI modülü başarıyla yüklendi!</p><p>Bu modal, fiBsModal fonksiyonu kullanılarak oluşturuldu.</p>`, "orakSoftUiModal",false);

//let fkb = new Fikeybean();

//import { Fdr } from './libs/oraksoft-ui.js';

console.log(testOrakSoftUi("dünya"));
//let fiCol = new FiCol();