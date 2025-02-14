import * as ko from "knockout";

class AppViewModel {
    message = ko.observable("Merhaba, Knockout.js ve Vite!");

    updateMessage() {
        this.message("Güncellendi!");
    }
}

const vm = new AppViewModel();
ko.applyBindings(vm);