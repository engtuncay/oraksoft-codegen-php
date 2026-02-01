import { FiKeybean, fiPostFormData, fiPostJson, testOrakSoftUi } from "../../orak_modules/oraksoft-ui/oraksoft-ui.js";


//let fkb = new FiKeybean;
//fkb.fiPut("a", "value");

//console.log(testOrakSoftUi("dünya"));



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
      //fiBsModal('<pre>' + data.result.refValue + '</pre>');
    }).catch((err) => {
      alert('Hata: ' + err);
    });
}

export function actReadEntities() {
  console.log("actReadEntities method called");
}





