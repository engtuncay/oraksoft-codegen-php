//import { FiKeybean } from "./libs/oraksoft-ui.js";

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

let eleSelPhp = document.getElementById("selPhp");

addOption(eleSelPhp,txIdSeciniz,"Seçiniz");
addOption(eleSelPhp,"1","FiCol Php Sınıf");
addOption(eleSelPhp,"2","FiMeta Php Sınıf");

let eleSelTs = document.getElementById("selTs");

addOption(eleSelTs,txIdSeciniz,"Seçiniz");
addOption(eleSelTs,"1","FiCol Ts Sınıf");

//let fkb = new Fikeybean();

//import { Fdr } from './libs/oraksoft-ui.js';
//console.log(greet("dünya"));
//let fiCol = new FiCol();