import modelsByMake from './masinuModeliai.js';

document.getElementById("marke").addEventListener("change", function () {
    const marke = this.value;
    const modelSelect = document.getElementById("model");

    modelSelect.innerHTML = '<option value="">Modelis</option>'; // Reset model dropdown

    if (marke && modelsByMake[marke]) {
        modelsByMake[make].forEach(model => {
            const option = document.createElement("option");
            option.value = model.toLowerCase();
            option.textContent = model;
            modelSelect.appendChild(option);
        });
    }
});
