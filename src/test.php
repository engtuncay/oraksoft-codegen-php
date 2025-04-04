<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vite + TS</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<label for="combo2" class="combo-label">Editable Combobox Example</label>

<!-- js-combobox class  -->
<div class="combo js-combobox">
  <input
    aria-activedescendant=""
    aria-autocomplete="none"
    aria-controls="listbox2"
    aria-expanded="false"
    aria-haspopup="listbox"
    id="combo2"
    class="combo-input"
    role="combobox"
    type="text"
  />
  <div class="combo-menu" role="listbox" id="listbox2"></div>
</div>

<label id="combo3-label" class="combo-label"
>Multi-select Combobox Example</label
>

<span id="combo3-remove" style="display: none">remove</span>
<!-- used as descriptive text for option buttons; if used within the button text itself, it ends up being read with the input name -->

<ul class="selected-options" id="combo3-selected"></ul>
<div class="combo js-multiselect">
  <input
    aria-activedescendant=""
    aria-autocomplete="none"
    aria-controls="listbox3"
    aria-expanded="false"
    aria-haspopup="listbox"
    aria-labelledby="combo3-label combo3-selected"
    id="combo3"
    class="combo-input"
    role="combobox"
    type="text"
  />
  <div class="combo-menu" role="listbox" id="listbox3"></div>
</div>

<!-- read only  -->
<p>Read Only Combobox</p>

<label id="combo1-label" class="combo-label"
>Read-only Select Example</label
>
<div class="combo js-select">
  <div
    aria-activedescendant="combo1-value"
    aria-autocomplete="none"
    aria-controls="listbox1"
    aria-expanded="false"
    aria-haspopup="listbox"
    aria-labelledby="combo1-label"
    id="combo1"
    class="combo-input"
    role="combobox"
    tabindex="0"
  >
    <span class="combo1-value" id="combo1-value"></span>
  </div>
  <div class="combo-menu" role="listbox" id="listbox1"></div>
</div>


<label for="combo2" class="combo-label">Editable Combobox Example (2)</label>

<!-- js-combobox class  -->
<div class="combo js-combobox2">
  <input
    aria-activedescendant=""
    aria-autocomplete="none"
    aria-controls="listbox2"
    aria-expanded="false"
    aria-haspopup="listbox"
    id="combo2"
    class="combo-input"
    role="combobox"
    type="text"
  />
  <div class="combo-menu" role="listbox" id="listbox2"></div>
</div>

<h1 data-bind="text: message"></h1>
<button data-bind="click: updateMessage">Mesajı Güncelle</button>

<script type="module" src="main.ts"></script>
</body>
</html>