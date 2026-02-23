import { FiKeybean, FiMeta, fiPostFormData, fiPostJson, testOrakSoftUi } from "../orak_modules/oraksoft-ui/oraksoft-ui.js";
import { FimCdgMssqlOpts } from "./ocgmeta/FimCdgMssqlOpts.js";
import { FimOsfFdr } from "./ocgmeta/FimOsfFdr.js";


/**
 * 
 * @param {HTMLElement} element 
 * @param {string} value 
 * @param {string} textContent 
 */
function addOption(element, value, textContent) {

  if (element instanceof HTMLSelectElement) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = textContent;
    element.appendChild(option);
  }

}

/**
 * 
 * @param {HTMLElement} element 
 * @param {FiMeta} fiMeta
 * 
 */
function addOptionByFimAndLnKey(element, fiMeta) {

  if (element instanceof HTMLSelectElement) {
    const option = document.createElement("option");
    //fiMeta.ftLnKey == null → true olur, eğer değer null veya undefined ise.
    option.value = (fiMeta.ftLnKey != null) ? fiMeta.ftLnKey.toString() : "";
    option.textContent = fiMeta.getTxValueNtn();
    element.appendChild(option);
  }

}

/**
 * FiCol ve FiMeta ve FkbCol sınıfları için option ekleme fonksiyonu
 * 
 * @param {HTMLElement} elementById 
 * @param {string} txIdSeciniz 
 */
function addFiClassOptsToElem(elementById, txIdSeciniz = "0") {

  if (elementById instanceof HTMLSelectElement) {
    addOption(elementById, txIdSeciniz, "Select");
    addOption(elementById, "2", "FiMeta Class");
    addOption(elementById, "3", "FkbCol Class");
    addOption(elementById, "1", "FiCol Class");
    //addOption(elementById, "4", "FiMeta Class");
  }

}

export function viewHomeInit() {

  let txIdSeciniz = "0";

  let eleSelCsharp = document.getElementById("selCsharp");
  addFiClassOptsToElem(eleSelCsharp);

  let eleSelPhp = document.getElementById("selPhp");
  addFiClassOptsToElem(eleSelPhp);

  let eleSelTs = document.getElementById("selTs");
  addFiClassOptsToElem(eleSelTs);

  let eleSelJs = document.getElementById("selJs");
  addFiClassOptsToElem(eleSelJs);

  let eleSelJava = document.getElementById("selJava");
  addFiClassOptsToElem(eleSelJava);

  let eleSelSql = document.getElementById("selSql");
  addOption(eleSelSql, txIdSeciniz, "Select");
  addOptionByFimAndLnKey(eleSelSql, FimCdgMssqlOpts.mssqlCreateTable());
  addOptionByFimAndLnKey(eleSelSql, FimCdgMssqlOpts.mssqlAlterTable());

  // Reset other selects when one changes
  const codeSelects = [eleSelCsharp, eleSelPhp, eleSelTs, eleSelJava, eleSelSql, eleSelJs];

  function resetOtherSelects(changedSelect) {
    codeSelects.forEach(s => {
      if (!(s instanceof HTMLSelectElement)) return;
      if (s === changedSelect) return;
      s.value = txIdSeciniz;
    });
  }

  codeSelects.forEach(s => {
    if (!(s instanceof HTMLSelectElement)) return;
    s.addEventListener('change', function () {
      //console.log("changed");
      resetOtherSelects(this);
    });
  });

}


//import { fiBsModal, testOrakSoftUi } from '../../orak_modules/oraksoft-ui/oraksoft-ui.js';
//fiBsModal(`<p>OrakSoft UI modülü başarıyla yüklendi!</p>`,"orakSoftUiModal",false);

//let fkb = new Fikeybean();
//import { Fdr } from './libs/oraksoft-ui.js';

//console.log(testOrakSoftUi("dünya"));
//let fiCol = new FiCol();



//console.log(testOrakSoftUi("dünya"));
// function addOption(element, value, textContent) {
//   const option = document.createElement("option");
//   option.value = value;
//   option.textContent = textContent;
//   element.appendChild(option);
// }


export function actReadEntityList() {

  console.log("actReadEntityList method called");

  let eleInputFile = document.getElementById("excelFile");

  // Eğer dosya seçilmediyse işlemi durdur
  if (!(eleInputFile instanceof HTMLInputElement) || !eleInputFile.files || eleInputFile.files.length === 0) {
    alert('Please select a DML file.');
    return;
  }

  const form = document.querySelector('form');

  // Convert form element to FormData before sending
  const formData = new FormData(form);

  fiPostFormData('/getEntityList', formData)
    .then((result) => {
      console.log(result);
      return result.json();
    })
    .then((data) => {
      console.log(data);
      if (data.entities) {
        /** {HTMLSelectElement} eleSelEntity */
        let eleSelEntity = document.getElementById("selEntity");
        if (!(eleSelEntity instanceof HTMLSelectElement)) return;
        eleSelEntity.options.length = 0;

        /** {string} entity */
        data.entities.forEach(entity => {
          addOption(eleSelEntity, entity, entity);
        });

      }
      //fiBsModal('<pre>' + data.result.refValue + '</pre>');
    }).catch((err) => {
      alert('Hata: ' + err);
    });
}

export function actGenCode() {
  console.log("actGenCode method called");

  const form = document.querySelector('form');

  // Convert form element to FormData before sending
  const formData = new FormData(form);

  fiPostFormData('/genCode', formData)
    .then((result) => {
      console.log(result);
      return result.json();
    })
    .then((data) => {
      console.log(data);
      let txCode = data[FimOsfFdr.fdTxValue().ftTxKey];
      //console.log('txcode:'+ txCode);
      if (txCode) {
        /** {HTMLElement} eleEntity */
        let eleEntity = document.getElementById("divCodeBlock");
        if (!(eleEntity instanceof HTMLElement)) return;
        eleEntity.innerHTML = '';
        eleEntity.innerHTML = '<pre>' + txCode + '</pre>';
      }
      //fiBsModal('<pre>' + data.result.refValue + '</pre>');
    }).catch((err) => {
      alert('Hata: ' + err);
    });
}

export function actReadDmlTestPost() {
  console.log("actReadDmlTestPost method called");

  fiPostJson('/testpost', {
    veri: 'örnek'
  })
    .then((result) => {
      console.log(result);
      return result.json();
    })
    .then((data) => {
      console.log(data);
      //fiBsModal('<pre>' + data.result.refValue + '</pre>');
    }).catch((err) => {
      alert('Hata: ' + err);
    });
}

export function actExecCommand() {
  console.log("actExecCommand method called");

  const form = document.querySelector('form');
  const formData = new FormData(form);

  fiPostFormData('/execCmd', formData)
    .then(async (result) => {
      let text = await result.text();
      let isJson = false;
      let data = null;
      try {
        data = JSON.parse(text);
        isJson = true;
      } catch (e) {
        isJson = false;
      }
      if (isJson) {
        // JSON ise ekrana yaz
        let eleEntity = document.getElementById("divCodeBlock");
        if (!(eleEntity instanceof HTMLElement)) return;
        eleEntity.innerHTML = '';
        eleEntity.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
      } else {
        // CSV ise dosya olarak indir
        const blob = new Blob([text], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        // Dosya adında timestamp ekle
        const timestamp = new Date().toISOString().replace(/[-:.TZ]/g, "");
        a.download = `export_${timestamp}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
      }
    })
    .catch((err) => {
      alert('Hata: ' + err);
    });
}