document.addEventListener('DOMContentLoaded', function() {

    function fetchModels(makeId, modelSelectElement) {
        modelSelectElement.innerHTML = '<option value="">Prašome palaukti...</option>';
        modelSelectElement.disabled = true;

        if (makeId) {
            if (typeof PHP_SCRIPTS_ROOT_PATH === 'undefined') {
                console.error('PHP_SCRIPTS_ROOT_PATH kintamasis nėra apibrėžtas HTML faile!');
                modelSelectElement.innerHTML = '<option value="">Klaida: Konfigūracija</option>';
                return;
            }
            const fetchUrl = `${PHP_SCRIPTS_ROOT_PATH}get_models.php?make_id=${makeId}`;
            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        console.error('Tinklo klaida gaunant modelius: ' + response.statusText, response);
                        throw new Error('Tinklo klaida: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(models => {
                    modelSelectElement.innerHTML = '<option value="">Pasirinkite modelį</option>';
                    if (models.length === 0) {
                        modelSelectElement.innerHTML = '<option value="">Modelių nerasta</option>';
                    } else {
                        models.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model.id;
                            option.textContent = model.name;
                            modelSelectElement.appendChild(option);
                        });
                    }
                    modelSelectElement.disabled = false;
                })
                .catch(error => {
                    console.error('Klaida gaunant modelius:', error);
                    modelSelectElement.innerHTML = '<option value="">Klaida kraunant modelius</option>';
                    modelSelectElement.disabled = false;
                });
        } else {
            modelSelectElement.innerHTML = '<option value="">Pasirinkite modelį</option>';
            modelSelectElement.disabled = true;
        }
    }

    function fetchMakes(vehicleTypeId, makeSelectElement, modelSelectElement) {
        if (modelSelectElement) {
            modelSelectElement.innerHTML = '<option value="">Prašome palaukti...</option>';
            modelSelectElement.disabled = true;
        }
        makeSelectElement.innerHTML = '<option value="">Prašome palaukti...</option>';
        makeSelectElement.disabled = true;

        if (vehicleTypeId) {
            if (typeof PHP_SCRIPTS_ROOT_PATH === 'undefined') {
                console.error('PHP_SCRIPTS_ROOT_PATH kintamasis nėra apibrėžtas HTML faile!');
                makeSelectElement.innerHTML = '<option value="">Klaida: Konfigūracija</option>';
                if (modelSelectElement) modelSelectElement.innerHTML = '<option value="">Klaida: Konfigūracija</option>';
                return;
            }
            const fetchUrl = `${PHP_SCRIPTS_ROOT_PATH}get_makes.php?vehicle_type_id=${vehicleTypeId}`;
            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        console.error('Tinklo klaida gaunant markes: ' + response.statusText, response);
                        throw new Error('Tinklo klaida: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(makes => {
                    makeSelectElement.innerHTML = '<option value="">Pasirinkite markę</option>';
                    if (makes.length === 0) {
                        makeSelectElement.innerHTML = '<option value="">Markių nerasta</option>';
                    } else {
                        makes.forEach(make => {
                            const option = document.createElement('option');
                            option.value = make.id;
                            option.textContent = make.name;
                            makeSelectElement.appendChild(option);
                        });
                    }
                    makeSelectElement.disabled = false;
                    if(modelSelectElement){
                        modelSelectElement.innerHTML = '<option value="">Pasirinkite modelį</option>';
                        modelSelectElement.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Klaida gaunant markes:', error);
                    makeSelectElement.innerHTML = '<option value="">Klaida kraunant markes</option>';
                    makeSelectElement.disabled = false;
                    if(modelSelectElement){
                         modelSelectElement.innerHTML = '<option value="">Pasirinkite modelį</option>';
                         modelSelectElement.disabled = true;
                    }
                });
        } else {
            makeSelectElement.innerHTML = '<option value="">Pasirinkite markę</option>';
            makeSelectElement.disabled = true;
            if(modelSelectElement){
                modelSelectElement.innerHTML = '<option value="">Pasirinkite modelį</option>';
                modelSelectElement.disabled = true;
            }
        }
    }

    const indexMakeSelect = document.getElementById('make');
    const indexModelSelect = document.getElementById('model');

    if (indexMakeSelect && indexModelSelect) {
        indexMakeSelect.addEventListener('change', function() {
            fetchModels(this.value, indexModelSelect);
        });
    }

    const newListingMakeSelect = document.getElementById('make_id');
    const newListingModelSelect = document.getElementById('model_id');

    if (newListingMakeSelect && newListingModelSelect) {
        newListingMakeSelect.addEventListener('change', function() {
            fetchModels(this.value, newListingModelSelect);
        });
    }

    const searchTabsButtons = document.querySelectorAll('.search-tabs button');
    const indexVehicleTypeInput = document.getElementById('vehicle_type');
    const indexCarSpecificFields = document.getElementById('car-specific-fields');

    if (searchTabsButtons.length > 0 && indexVehicleTypeInput && indexMakeSelect && indexModelSelect) {
        function initializeIndexSearch() {
            const initiallyActiveTab = document.querySelector('.search-tabs button.active');
            let initialVehicleType = '1'; 

            if (initiallyActiveTab) {
                initialVehicleType = initiallyActiveTab.dataset.vehicleType;
            } else if (searchTabsButtons.length > 0) {
                searchTabsButtons[0].classList.add('active');
                initialVehicleType = searchTabsButtons[0].dataset.vehicleType;
            }
            
            if(indexVehicleTypeInput.value !== initialVehicleType){
                 indexVehicleTypeInput.value = initialVehicleType;
            }
            fetchMakes(initialVehicleType, indexMakeSelect, indexModelSelect);

            if (indexCarSpecificFields) {
                indexCarSpecificFields.style.display = (initialVehicleType == 1) ? 'block' : 'none';
            }
        }
        
        initializeIndexSearch();

        searchTabsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currentActive = document.querySelector('.search-tabs button.active');
                if (currentActive) {
                    currentActive.classList.remove('active');
                }
                this.classList.add('active');
                
                const vehicleType = this.dataset.vehicleType;
                indexVehicleTypeInput.value = vehicleType;
                
                fetchMakes(vehicleType, indexMakeSelect, indexModelSelect);
                
                if (indexCarSpecificFields) {
                    indexCarSpecificFields.style.display = (vehicleType == 1) ? 'block' : 'none';
                    const bodyTypeSelect = indexCarSpecificFields.querySelector('select[name="body_type"]');
                    if (bodyTypeSelect && vehicleType != 1) {
                       bodyTypeSelect.value = "";
                    }
                }
            });
        });
    }

    const newListingVehicleTypeSelect = document.getElementById('vehicle_type_id');
    const newListingCarSpecificFieldsContainer = document.getElementById('car-specific-fields');

    if (newListingVehicleTypeSelect && newListingMakeSelect && newListingModelSelect) {
        function updateCarSpecificFieldsStateNewListing() {
            const vehicleType = newListingVehicleTypeSelect.value;
            const bodyTypeField = newListingCarSpecificFieldsContainer ? newListingCarSpecificFieldsContainer.querySelector('#body_type') : null;
            const transmissionField = newListingCarSpecificFieldsContainer ? newListingCarSpecificFieldsContainer.querySelector('#transmission') : null;

            if (newListingCarSpecificFieldsContainer) {
                 newListingCarSpecificFieldsContainer.style.display = (vehicleType == 1) ? 'block' : 'none';
            }

            if (bodyTypeField) {
                bodyTypeField.required = (vehicleType == 1);
                if (vehicleType != 1) bodyTypeField.value = ''; 
            }
            if (transmissionField) {
                transmissionField.required = (vehicleType == 1);
                if (vehicleType != 1) transmissionField.value = ''; 
            }
        }
        
        newListingVehicleTypeSelect.addEventListener('change', function() {
            const vehicleType = this.value;
            fetchMakes(vehicleType, newListingMakeSelect, newListingModelSelect);
            updateCarSpecificFieldsStateNewListing();
        });
        
        updateCarSpecificFieldsStateNewListing();
        
        if (newListingVehicleTypeSelect.value) {
            const preselectedMakeId = newListingMakeSelect.value;
            
            if(!preselectedMakeId) { 
                 fetchMakes(newListingVehicleTypeSelect.value, newListingMakeSelect, newListingModelSelect);
            } else { 
                 const preselectedModelIdValue = (typeof newListingModelSelect.dataset.preselectedModel !== 'undefined') ? newListingModelSelect.dataset.preselectedModel : newListingModelSelect.value;
                 const modelsAreLoaded = newListingModelSelect.options.length > 1 && newListingModelSelect.options[1].value !== "";

                 if(!preselectedModelIdValue && !modelsAreLoaded) {
                    fetchModels(preselectedMakeId, newListingModelSelect);
                 } else if (preselectedModelIdValue && modelsAreLoaded) {
                    let modelExists = false;
                    for(let i=0; i < newListingModelSelect.options.length; i++) {
                        if(newListingModelSelect.options[i].value == preselectedModelIdValue) {
                            modelExists = true;
                            newListingModelSelect.value = preselectedModelIdValue; 
                            break;
                        }
                    }
                    if (!modelExists) {
                         fetchModels(preselectedMakeId, newListingModelSelect);
                    }
                 } else if (preselectedModelIdValue && !modelsAreLoaded) {
                     fetchModels(preselectedMakeId, newListingModelSelect);
                 }
            }
        }
    }

    const newListingForm = document.getElementById('new-listing-form');
    if (newListingForm) {
        newListingForm.addEventListener('submit', function(e) {
            const priceInput = document.getElementById('price');
            const mileageInput = document.getElementById('mileage');
            const powerInput = document.getElementById('power');
            const engineInput = document.getElementById('engine_capacity');
            const modelInput = document.getElementById('model_id');
            const vinInput = document.getElementById('vin');
            
            let errors = [];

            if (!modelInput || !modelInput.value) {
                errors.push('Pasirinkite modelį!');
            }
            
            if (priceInput && priceInput.value.trim() === '') {
                errors.push('Kainos laukas yra privalomas.');
            } else if (priceInput && (parseFloat(priceInput.value) < 0 || isNaN(parseFloat(priceInput.value)))) {
                 errors.push('Kaina turi būti teigiamas skaičius arba 0!');
            }
            
            if (mileageInput && mileageInput.value.trim() === '') {
                errors.push('Ridos laukas yra privalomas.');
            } else if (mileageInput && mileageInput.value.trim() !== '' && (parseInt(mileageInput.value) < 0 || isNaN(parseInt(mileageInput.value)))) {
                errors.push('Rida negali būti neigiama!');
            }

            if (powerInput && powerInput.value.trim() !== '' && (parseInt(powerInput.value) <= 0 || isNaN(parseInt(powerInput.value)))) {
                errors.push('Galia turi būti teigiamas skaičius!');
            }
            
            if (engineInput && engineInput.value.trim() !== '' && (parseFloat(engineInput.value) <= 0 || isNaN(parseFloat(engineInput.value)))) {
                errors.push('Variklio tūris turi būti teigiamas skaičius!');
            }
            
            if (vinInput && vinInput.value.trim() && vinInput.value.trim().length !== 17) {
                errors.push('VIN kodas turi būti lygiai 17 simbolių!');
            }

            if (errors.length > 0) {
                e.preventDefault(); 
                alert(errors.join('\n')); 
                return false;
            }
        });
    }

    const greetingElement = document.getElementById('greeting');
    if (greetingElement && greetingElement.textContent.trim() !== '') {
        setTimeout(function() {
            greetingElement.style.opacity = '0';
            setTimeout(function() {
                if (greetingElement.parentNode) { 
                    greetingElement.parentNode.removeChild(greetingElement);
                }
            }, 1000); 
        }, 1500); 
    }
});
const imageUploadsInput = document.getElementById('image_uploads');
    const imagePreviewsContainer = document.getElementById('image_previews_container');
    const primaryImageIndexInput = document.getElementById('primary_image_index');

    if (imageUploadsInput && imagePreviewsContainer && primaryImageIndexInput) {
        imageUploadsInput.addEventListener('change', function(event) {
            imagePreviewsContainer.innerHTML = '';
            primaryImageIndexInput.value = '0';

            const files = event.target.files;
            if (files.length === 0) {
                const p = document.createElement('p');
                p.textContent = 'Nepasirinkta jokių nuotraukų.';
                imagePreviewsContainer.appendChild(p);
                return;
            }

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('img-preview-wrapper');
                    if (index === 0) {
                        wrapper.classList.add('primary');
                    }
                    wrapper.dataset.index = index;

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;

                    const indicator = document.createElement('span');
                    indicator.classList.add('primary-indicator');
                    indicator.textContent = 'Pagrindinė';

                    wrapper.appendChild(img);
                    wrapper.appendChild(indicator);
                    imagePreviewsContainer.appendChild(wrapper);

                    wrapper.addEventListener('click', function() {
                        imagePreviewsContainer.querySelectorAll('.img-preview-wrapper').forEach(el => {
                            el.classList.remove('primary');
                        });
                        this.classList.add('primary');
                        primaryImageIndexInput.value = this.dataset.index;
                    });
                }
                reader.readAsDataURL(file);
            });
        });
        if (imagePreviewsContainer.children.length === 0) {
             const p = document.createElement('p');
             p.textContent = 'Nepasirinkta jokių nuotraukų.';
             imagePreviewsContainer.appendChild(p);
        }
    }
const imageUploadsInputEdit = document.getElementById('image_uploads_edit');
const imagePreviewsContainerEdit = document.getElementById('new_image_previews_container_edit');
const editListingForm = document.getElementById('edit-listing-form');
const submitEditFormBtn = document.getElementById('submit-edit-form-btn');
const primaryNewImageIdentifierInputEdit = document.getElementById('primary_new_image_identifier_edit');
let newFilesDataEdit = [];

if (imageUploadsInputEdit && imagePreviewsContainerEdit && editListingForm && submitEditFormBtn && primaryNewImageIdentifierInputEdit) {

    imageUploadsInputEdit.addEventListener('change', function(event) {
        const newFiles = Array.from(event.target.files);
        newFilesDataEdit = newFiles.map((file, index) => ({
            file: file,
            id: `new_${Date.now()}_${index}`
        }));
        
        primaryNewImageIdentifierInputEdit.value = "";
        if (newFilesDataEdit.length > 0) {
            const existingPrimaryRadio = editListingForm.querySelector('input[name="primary_image_id"]:checked');
            let isExistingPrimarySelectedAndNotDeleted = false;
            if (existingPrimaryRadio) {
                const existingWrapper = existingPrimaryRadio.closest('.img-preview-wrapper-existing');
                const deleteCheckbox = existingWrapper ? existingWrapper.querySelector('input[name="delete_images[]"]') : null;
                if (!deleteCheckbox || !deleteCheckbox.checked) {
                    isExistingPrimarySelectedAndNotDeleted = true;
                }
            }

            if (!isExistingPrimarySelectedAndNotDeleted) {
                primaryNewImageIdentifierInputEdit.value = newFilesDataEdit[0].id;
                if(existingPrimaryRadio) existingPrimaryRadio.checked = false;
            }
        }
        renderNewImagePreviewsEdit();
    });

    function renderNewImagePreviewsEdit() {
        imagePreviewsContainerEdit.innerHTML = '';
        if (newFilesDataEdit.length === 0) {
            const p = document.createElement('p');
            p.textContent = 'Nepasirinkta naujų nuotraukų.';
            imagePreviewsContainerEdit.appendChild(p);
            return;
        }

        const currentNewPrimaryId = primaryNewImageIdentifierInputEdit.value;

        newFilesDataEdit.forEach((fileData) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('img-preview-wrapper');
                if (fileData.id === currentNewPrimaryId) {
                    wrapper.classList.add('primary');
                }
                wrapper.dataset.fileId = fileData.id;

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = fileData.file.name;

                const primaryIndicator = document.createElement('span');
                primaryIndicator.classList.add('primary-indicator');
                primaryIndicator.textContent = 'Pagrindinė';

                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.classList.add('delete-new-preview-btn');
                deleteBtn.innerHTML = '&times;';
                deleteBtn.title = 'Pašalinti';

                wrapper.addEventListener('click', function() {
                    imagePreviewsContainerEdit.querySelectorAll('.img-preview-wrapper').forEach(el => el.classList.remove('primary'));
                    const existingRadios = editListingForm.querySelectorAll('input[name="primary_image_id"]');
                    existingRadios.forEach(radio => {
                        radio.checked = false;
                        const radioWrapper = radio.closest('.img-preview-wrapper-existing');
                        if(radioWrapper) radioWrapper.classList.remove('primary');
                    });
                    this.classList.add('primary');
                    primaryNewImageIdentifierInputEdit.value = this.dataset.fileId;
                });

                deleteBtn.addEventListener('click', function(event) {
                    event.stopPropagation();
                    const fileIdToRemove = wrapper.dataset.fileId;
                    if (primaryNewImageIdentifierInputEdit.value === fileIdToRemove) {
                        primaryNewImageIdentifierInputEdit.value = "";
                    }
                    newFilesDataEdit = newFilesDataEdit.filter(fd => fd.id !== fileIdToRemove);
                    renderNewImagePreviewsEdit();
                });
                
                wrapper.appendChild(img);
                wrapper.appendChild(primaryIndicator);
                wrapper.appendChild(deleteBtn);
                imagePreviewsContainerEdit.appendChild(wrapper);
            }
            reader.readAsDataURL(fileData.file);
        });
    }

    submitEditFormBtn.addEventListener('click', function() {
        const formData = new FormData(editListingForm);
        formData.delete('image_uploads[]'); 
        newFilesDataEdit.forEach(fileDataObject => {
            formData.append('image_uploads[]', fileDataObject.file, fileDataObject.file.name);
        });

        fetch(editListingForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok && response.redirected) {
                window.location.href = response.url; 
            } else if (response.ok) { 
                window.location.reload(); 
            } else {
                response.text().then(text => {
                    console.error('Klaida:', text);
                    alert('Klaida atnaujinant skelbimą.');
                });
            }
        })
        .catch(error => {
            console.error('Tinklo klaida:', error);
            alert('Tinklo klaida.');
        });
    });
    
    const existingRadiosInEdit = editListingForm.querySelectorAll('input[name="primary_image_id"]');
    existingRadiosInEdit.forEach(radio => {
        const wrapper = radio.closest('.img-preview-wrapper-existing'); 
        if (wrapper) {
             if (radio.checked) {
                wrapper.classList.add('primary');
            }
            radio.addEventListener('change', function() {
                if (this.checked) {
                    if(primaryNewImageIdentifierInputEdit) primaryNewImageIdentifierInputEdit.value = ""; 
                    imagePreviewsContainerEdit.querySelectorAll('.img-preview-wrapper').forEach(el => el.classList.remove('primary'));
                    editListingForm.querySelectorAll('.img-preview-wrapper-existing').forEach(el => el.classList.remove('primary'));
                    wrapper.classList.add('primary');
                }
            });
            const deleteCheckbox = wrapper.querySelector('input[name="delete_images[]"]');
            if (deleteCheckbox) {
                deleteCheckbox.addEventListener('change', function(){
                    if (this.checked && wrapper.classList.contains('primary')) {
                        radio.checked = false; 
                        wrapper.classList.remove('primary');
                        const firstAvailableRadio = Array.from(existingRadiosInEdit).find(r => {
                            const w = r.closest('.img-preview-wrapper-existing');
                            const cb = w ? w.querySelector('input[name="delete_images[]"]') : null;
                            return !cb || !cb.checked;
                        });
                        if (firstAvailableRadio) {
                            firstAvailableRadio.checked = true;
                            const firstAvailableWrapper = firstAvailableRadio.closest('.img-preview-wrapper-existing');
                            if (firstAvailableWrapper) firstAvailableWrapper.classList.add('primary');
                        } else if (newFilesDataEdit.length > 0 && primaryNewImageIdentifierInputEdit) {
                            primaryNewImageIdentifierInputEdit.value = newFilesDataEdit[0].id;
                            renderNewImagePreviewsEdit(); 
                        }
                    }
                });
            }
        }
    });

    if (newFilesDataEdit.length === 0 && imagePreviewsContainerEdit.children.length === 0) {
         const p = document.createElement('p');
         p.textContent = 'Nepasirinkta naujų nuotraukų.';
         imagePreviewsContainerEdit.appendChild(p);
    } else if (newFilesDataEdit.length > 0) { 
        renderNewImagePreviewsEdit();
    }
}