import * as ko from "knockout";

function AppViewModel() {
    var self = this;

    // Renk seçenekleri
    self.colors = [
        {name: "Kırmızı", code: "#FF0000"},
        {name: "Yeşil", code: "#00FF00"},
        {name: "Mavi", code: "#0000FF"},
        {name: "Sarı", code: "#FFFF00"}
    ];

    // Seçilen renk
    self.selectedColor = ko.observable(self.colors[0].code); // Varsayılan olarak ilk renk seçili

    // Seçilen renk değeri değiştikçe burada bir şeyler yapılabilir.
}

ko.applyBindings(new AppViewModel());