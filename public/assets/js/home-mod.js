import { FiKeybean, fiPostFormData, fiPostJson, testOrakSoftUi } from "../../orak_modules/oraksoft-ui/oraksoft-ui.js";


//console.log(testOrakSoftUi("dünya"));
function addOption(element, value, textContent) {
  const option = document.createElement("option");
  option.value = value;
  option.textContent = textContent;
  element.appendChild(option);
}


export function actReadDml() {
  console.log("actReadDml method called");

  const form = document.querySelector('form');

  // Convert form element to FormData before sending
  const formData = new FormData(form);

  fiPostFormData('/getEntities', formData)
    .then((result) => {
      console.log(result);
      return result.json();
    })
    .then((data) => {
      console.log(data);
      if (data.entities) {
        /** {HTMLSelectElement} eleSelEntities */
        let eleSelEntities = document.getElementById("selEntities");
        if (!(eleSelEntities instanceof HTMLSelectElement)) return;
        eleSelEntities.options.length = 0;

        /** {string} entity */
        data.entities.forEach(entity => {
          addOption(eleSelEntities, entity, entity);
        });
        
      }
      //fiBsModal('<pre>' + data.result.refValue + '</pre>');
    }).catch((err) => {
      alert('Hata: ' + err);
    });
}

export function actReadEntities() {
  console.log("actReadEntities method called");
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
