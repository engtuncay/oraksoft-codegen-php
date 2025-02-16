//import { FiKeybean } from "./libs/oraksoft-ui.js";

let elementById = document.getElementById("opsCsharp");

function addOption(element, value,textContent) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = textContent;
    element.appendChild(option);
}

addOption(elementById,"-1","Seçiniz");
addOption(elementById,"1","FiCol");
addOption(elementById,"2","opt2");

//let fkb = new Fikeybean();

//import { Fdr } from './libs/oraksoft-ui.js';
//console.log(greet("dünya"));
//let fiCol = new FiCol();