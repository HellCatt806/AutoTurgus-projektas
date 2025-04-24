
document.getElementById('make')?.addEventListener('change', function() {
    const makeId = this.value;
    const modelSelect = document.getElementById('model');
    
    modelSelect.innerHTML = '<option value="">Pasirinkite modelį</option>';
    modelSelect.disabled = !makeId;
    
    if (makeId) {
        fetch(`api/get_models.php?make_id=${makeId}`)
            .then(response => response.json())
            .then(models => {
                models.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model.id;
                    option.textContent = model.name;
                    modelSelect.appendChild(option);
                });
                modelSelect.disabled = false;
            })
            .catch(error => console.error('Klaida:', error));
    }
});


document.getElementById('make_id')?.addEventListener('change', function() {
    const makeId = this.value;
    const modelSelect = document.getElementById('model_id');
    
    modelSelect.innerHTML = '<option value="">Pasirinkite modelį</option>';
    modelSelect.disabled = !makeId;
    
    if (makeId) {
        fetch(`api/get_models.php?make_id=${makeId}`)
            .then(response => response.json())
            .then(models => {
                models.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model.id;
                    option.textContent = model.name;
                    modelSelect.appendChild(option);
                });
                modelSelect.disabled = false;
            })
            .catch(error => console.error('Klaida:', error));
    }
});


document.querySelectorAll('.search-tabs button')?.forEach(button => {
    button.addEventListener('click', function() {
        document.querySelector('.search-tabs button.active').classList.remove('active');
        this.classList.add('active');
        document.getElementById('vehicle_type').value = this.dataset.vehicleType;
        
       
        const makeSelect = document.getElementById('make');
        makeSelect.innerHTML = '<option value="">Pasirinkite markę</option>';
        document.getElementById('model').innerHTML = '<option value="">Pasirinkite modelį</option>';
        document.getElementById('model').disabled = true;
        
        if (this.dataset.vehicleType) {
            fetch(`api/get_makes.php?vehicle_type_id=${this.dataset.vehicleType}`)
                .then(response => response.json())
                .then(makes => {
                    makes.forEach(make => {
                        const option = document.createElement('option');
                        option.value = make.id;
                        option.textContent = make.name;
                        makeSelect.appendChild(option);
                    });
                });
        }
        
        
        const carFields = document.getElementById('car-specific-fields');
        if (carFields) {
            carFields.style.display = this.dataset.vehicleType == 1 ? 'block' : 'none';
        }
    });
});


document.getElementById('vehicle_type_id')?.addEventListener('change', function() {
    const vehicleType = this.value;
    const carFields = document.getElementById('car-specific-fields');
    
    if (carFields) {
        carFields.style.display = vehicleType == 1 ? 'block' : 'none';
    }
    
    
    const makeSelect = document.getElementById('make_id');
    makeSelect.innerHTML = '<option value="">Pasirinkite markę</option>';
    document.getElementById('model_id').innerHTML = '<option value="">Pasirinkite modelį</option>';
    document.getElementById('model_id').disabled = true;
    
    if (vehicleType) {
        fetch(`api/get_makes.php?vehicle_type_id=${vehicleType}`)
            .then(response => response.json())
            .then(makes => {
                makes.forEach(make => {
                    const option = document.createElement('option');
                    option.value = make.id;
                    option.textContent = make.name;
                    makeSelect.appendChild(option);
                });
            });
    }
});


document.getElementById('new-listing-form')?.addEventListener('submit', function(e) {
    const price = parseFloat(document.getElementById('price').value);
    const mileage = parseInt(document.getElementById('mileage').value);
    const power = parseInt(document.getElementById('power').value);
    const engine = parseFloat(document.getElementById('engine_capacity').value);
    const model = document.getElementById('model_id').value;
    
    if (!model) {
        e.preventDefault();
        alert('Pasirinkite modelį!');
        return;
    }
    
    if (price <= 0 || isNaN(price)) {
        e.preventDefault();
        alert('Kaina turi būti teigiamas skaičius!');
        return;
    }
    
    if (mileage < 0 || isNaN(mileage)) {
        e.preventDefault();
        alert('Rida negali būti neigiama!');
        return;
    }
    
    if (power <= 0 || isNaN(power)) {
        e.preventDefault();
        alert('Galia turi būti teigiamas skaičius!');
        return;
    }
    
    if (engine <= 0 || isNaN(engine)) {
        e.preventDefault();
        alert('Variklio tūris turi būti teigiamas skaičius!');
        return;
    }
    
    const vin = document.getElementById('vin').value;
    if (vin && vin.length !== 17) {
        e.preventDefault();
        alert('VIN kodas turi būti lygiai 17 simbolių!');
        return;
    }
});