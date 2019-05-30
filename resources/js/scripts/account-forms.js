
/*--- Tomoscity Auth ---*/

const showValidationMessages = (messages) => {
    Object.keys(messages).forEach((name) => {
        let firstItemDOM = document.getElementsByName(name)[0];
        firstItemDOM.insertAdjacentHTML('afterend', `<div class="text-danger fs--1">${messages[name]}</div>`);
        firstItemDOM.classList.add('border', 'border-danger');
    });
};

const tomosDangerMessage = (message) => {
    message = message || 'Unknown Server Error';
    if (dangerMessage = document.querySelector('.alert-danger')) {
        dangerMessage.remove();
    }
    if (successMessage = document.querySelector('.alert-success')) {
        successMessage.remove();
    }
    let alerts = document.querySelector('#alerts');
    let html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    message + '!' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('beforebegin', html);
    const offset = 150;
    const bodyRect = document.body.getBoundingClientRect().top;
    const elementRect = alerts.getBoundingClientRect().top;
    const elementPosition = elementRect - bodyRect;
    const offsetPosition = elementPosition - offset;
    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
}

const tomosSuccessMessage = (message) => {
    message = message || 'Success! You did it all';
    if (dangerMessage = document.querySelector('.alert-danger')) {
        dangerMessage.remove();
    }
    if (successMessage = document.querySelector('.alert-success')) {
        successMessage.remove();
    }
    let alerts = document.querySelector('#alerts');
    let html = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    message + '!' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('beforebegin', html);
    const offset = 150;
    const bodyRect = document.body.getBoundingClientRect().top;
    const elementRect = alerts.getBoundingClientRect().top;
    const elementPosition = elementRect - bodyRect;
    const offsetPosition = elementPosition - offset;
    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
}

const clearErrors = () => {
    let errorMessages = document.querySelectorAll('.text-danger');
    errorMessages.forEach((item) => item.textContent = '');
    let formControls = document.querySelectorAll('.form-control');
    formControls.forEach((item) => item.classList.remove('border', 'border-danger'));
};

const buttonLoadingStart = (button, buttonText) => {
    button.setAttribute('disabled', "disabled");
    button.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + buttonText;
};

const buttonLoadingStop = (button, buttonText) => {
    button.innerHTML = buttonText;
    button.removeAttribute("disabled");
};

/*--- Tomos Registration ---*/

if (registrationForm = document.querySelector('#tomosRegistration')) {
    registrationForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = document.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(registrationForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(registrationForm.action, data)
            .then((response) => {
                clearErrors();
                if (url = response.data.url) {
                    window.location.href = url;
                } else {
                    tomosDangerMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                if (failure.response.data.errors) {
                    let messages = failure.response.data.messages;
                    showValidationMessages(messages);
                } else {
                    tomosDangerMessage();
                }
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Login ---*/

if (loginForm = document.querySelector('#tomosLogin')) {
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = document.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(loginForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(loginForm.action, data)
            .then((response) => {
                if (url = response.data.url) {
                    window.location.href = url;
                } else if (message = response.data.message) {
                    tomosDangerMessage(message);
                } else {
                    tomosDangerMessage();
                }
            })
            .catch((failure) => {
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Forgot Password ---*/

if (forgotForm = document.querySelector('#tomosForgot')) {
    forgotForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = document.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(forgotForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(forgotForm.action, data)
            .then((response) => {
                if (url = response.data.url) {
                    window.location.href = url;
                } else if (message = response.data.message) {
                    tomosDangerMessage(message);
                } else {
                    tomosDangerMessage();
                }
            })
            .catch((failure) => {
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Reset Password ---*/

if (resetForm = document.querySelector('#tomosReset')) {
    resetForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = document.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(resetForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(resetForm.action, data)
            .then((response) => {
                clearErrors();
                if (url = response.data.url) {
                    window.location.href = url;
               } else if (message = response.data.message) {
                    tomosDangerMessage(message);
                } else {
                    tomosDangerMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                if (failure.response.data.errors) {
                    let messages = failure.response.data.messages;
                    showValidationMessages(messages);
                } else {
                    tomosDangerMessage();
                }
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Account Profile ---*/

if (profileForm = document.querySelector('#tomosAccountProfile')) {
    profileForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = profileForm.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(profileForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(profileForm.action, data)
            .then((response) => {
                clearErrors();
                if (messages = response.data.messages) {
                    showValidationMessages(messages);
                } else if (success = response.data.success) {
                    tomosSuccessMessage(success);
                } else {
                    tomosSuccessMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Account Location ---*/

if (locationForm = document.querySelector('#tomosAccountLocation')) {
    const inputCity = locationForm.querySelector('#city');
    const selectCountry = locationForm.querySelector('#country');
    const searchResults = locationForm.querySelector('#searchResults');
    const geoLat  = locationForm.querySelector('input[name="geo_lat"]');
    const geoLon  = locationForm.querySelector('input[name="geo_lon"]');
    const mapZoom = '13';
    const maxZoom = '18';
    const iconUrl   = 'data:image/png;base64,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';
    const shadowUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACkAAAApCAYAAACoYAD2AAAC5ElEQVRYw+2YW4/TMBCF45S0S1luXZCABy5CgLQgwf//S4BYBLTdJLax0fFqmB07nnQfEGqkIydpVH85M+NLjPe++dcPc4Q8Qh4hj5D/AaQJx6H/4TMwB0PeBNwU7EGQAmAtsNfAzoZkgIa0ZgLMa4Aj6CxIAsjhjOCoL5z7Glg1JAOkaicgvQBXuncwJAWjksLtBTWZe04CnYRktUGdilALppZBOgHGZcBzL6OClABvMSVIzyBjazOgrvACf1ydC5mguqAVg6RhdkSWQFj2uxfaq/BrIZOLEWgZdALIDvcMcZLD8ZbLC9de4yR1sYMi4G20S4Q/PWeJYxTOZn5zJXANZHIxAd4JWhPIloTJZhzMQduM89WQ3MUVAE/RnhAXpTycqys3NZALOBbB7kFrgLesQl2h45Fcj8L1tTSohUwuxhy8H/Qg6K7gIs+3kkaigQCOcyEXCHN07wyQazhrmIulvKMQAwMcmLNqyCVyMAI+BuxSMeTk3OPikLY2J1uE+VHQk6ANrhds+tNARqBeaGc72cK550FP4WhXmFmcMGhTwAR1ifOe3EvPqIegFmF+C8gVy0OfAaWQPMR7gF1OQKqGoBjq90HPMP01BUjPOqGFksC4emE48tWQAH0YmvOgF3DST6xieJgHAWxPAHMuNhrImIdvoNOKNWIOcE+UXE0pYAnkX6uhWsgVXDxHdTfCmrEEmMB2zMFimLVOtiiajxiGWrbU52EeCdyOwPEQD8LqyPH9Ti2kgYMf4OhSKB7qYILbBv3CuVTJ11Y80oaseiMWOONc/Y7kJYe0xL2f0BaiFTxknHO5HaMGMublKwxFGzYdWsBF174H/QDknhTHmHHN39iWFnkZx8lPyM8WHfYELmlLKtgWNmFNzQcC1b47gJ4hL19i7o65dhH0Negbca8vONZoP7doIeOC9zXm8RjuL0Gf4d4OYaU5ljo3GYiqzrWQHfJxA6ALhDpVKv9qYeZA8eM3EhfPSCmpuD0AAAAASUVORK5CYII=';
    let markerIcon  = L.icon({
        iconUrl: iconUrl,
        iconAnchor: [12, 41],
        iconSize: [25, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
        shadowUrl: shadowUrl
    });
    let geolocationMap = L.map('geolocation', {
        center: [geoLat.value, geoLon.value],
        zoom: mapZoom
    });
    selectCountry.addEventListener('change', () => {
        inputCity.value = '';
        inputCity.focus();
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: maxZoom,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(geolocationMap);
    geolocationMarker = L.marker([geoLat.value, geoLon.value], {
        icon: markerIcon,
        draggable: true,
        autoPan:   true,
        autoPanPadding: L.point(100, 100)
    }).addTo(geolocationMap);
    geolocationMarker.addEventListener('dragend', () => {
        geoLat.value = geolocationMarker.getLatLng().lat;
        geoLon.value = geolocationMarker.getLatLng().lng;
    });
    geolocationMap.addEventListener('click', (e) => {
        geolocationMarker.setLatLng(e.latlng);
        geoLat.value = geolocationMarker.getLatLng().lat;
        geoLon.value = geolocationMarker.getLatLng().lng;
        geolocationMap.panTo(e.latlng);
    });
    inputCity.addEventListener('keyup', () => {
        let queryCity = inputCity.value;
        if (queryCity.length > 3) {
            let country = selectCountry.options[selectCountry.selectedIndex].text;
            let queryCountry = (country !== 'Select country...' && 'undefined') ? country : '';
            if (queryCountry == '') {
                return selectCountry.focus();
            }
            //let query = queryCountry + ', ' + queryCity;
            let query = queryCity;
            axios.get('https://nominatim.openstreetmap.org/search?format=json&limit=8&q=' + query)
                .then(response => {
                    let options = [];
                    let responseData = response.data;
                    responseData.forEach((val, index) => {
                        let optionItem = {
                            value: index,
                            text: val.display_name
                        };
                        options.push(optionItem);
                    });
                    searchResults.innerHTML = null;
                    if (options.length > 0) {
                        const selectResults = document.createElement('select');
                        selectResults.setAttribute('class', 'form-control my-2');
                        options.forEach(item => {
                            let option = document.createElement('option');
                            option.setAttribute('value', item.value);
                            option.innerHTML = item.text;
                            selectResults.appendChild(option);
                        });
                        searchResults.appendChild(selectResults);
                        selectResults.addEventListener('click', () => {
                            const id = selectResults.value;
                            const Geo = responseData[id];
                            const southWest = new L.LatLng(Geo.boundingbox[0], Geo.boundingbox[2]);
                            const northEast = new L.LatLng(Geo.boundingbox[1], Geo.boundingbox[3]);
                            const bounds = new L.LatLngBounds(southWest, northEast);
                            geolocationMap.fitBounds(bounds);
                            geolocationMarker.setLatLng([Geo.lat, Geo.lon]);
                            geoLat.value = geolocationMarker.getLatLng().lat;
                            geoLon.value = geolocationMarker.getLatLng().lng;
                        });
                        selectResults.focus();
                    } else {
                        searchResults.innerHTML = '<h5 class="fs--1 text-danger">No results found...</h5>';
                    }
                });
        }
    });

    locationForm.addEventListener('submit', event => {
        event.preventDefault();
        let submitButton = locationForm.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(locationForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(locationForm.action, data)
            .then(response => {
                clearErrors();
                if (messages = response.data.messages) {
                    showValidationMessages(messages);
                } else if (success = response.data.success) {
                    searchResults.innerHTML = null;
                    tomosSuccessMessage(success);
                } else {
                    tomosSuccessMessage();
                }
            })
            .catch(failure => {
                clearErrors();
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

/*--- Tomos Account Experience ---*/

if (experienceForm = document.querySelector('#tomosAccountExperience')) {
    experienceForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = experienceForm.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(experienceForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(experienceForm.action, data)
            .then((response) => {
                clearErrors();
                if (messages = response.data.messages) {
                    showValidationMessages(messages);
                } else if (success = response.data.success) {
                    //tomosSuccessMessage(success);
                    showExperienceItemBlock(success);
                } else {
                    tomosSuccessMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

const showExperienceItemBlock = (data) => {
    const experienceForm = document.querySelector('#experience-form');
    const html = '<div class="media alert-success mb-2" style="padding-top:16px; padding-bottom:10px;">' +
                    '<a href="#!">' +
                        '<img class="img-fluid" src="' + data.image + '" alt="" width="50">' +
                    '</a>' +
                    '<div class="media-body position-relative pl-3">' +
                        '<h6 class="fs-0 mb-0">' + data.position +
                            '<small class="fas fa-check-circle text-primary ml-1" data-toggle="tooltip" data-placement="top" title="Verified" data-fa-transform="shrink-4 down-2"></small>' +
                        '</h6>' +
                        '<p class="mb-1"><a href="#!">' + data.company + '</a></p>' +
                        '<p class="text-1000 mb-0">' + data.period + '</p>' +
                        '<p class="text-1000 mb-0">' + data.city + '</p>' +
                    '</div>' +
                '</div>';
    experienceForm.insertAdjacentHTML('afterend', html);
}

/*--- Tomos Account Education ---*/

if (educationForm = document.querySelector('#tomosAccountEducation')) {
    educationForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = educationForm.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(educationForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(educationForm.action, data)
            .then((response) => {
                clearErrors();
                if (messages = response.data.messages) {
                    showValidationMessages(messages);
                } else if (success = response.data.success) {
                    //tomosSuccessMessage(success);
                    showEducationItemBlock(success);
                } else {
                    tomosSuccessMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                tomosDangerMessage();
            })
            .then(() => {
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}

const showEducationItemBlock = (data) => {
    const educationForm = document.querySelector('#education-form');
    const html = '<div class="media alert-success mb-2" style="padding-top:16px; padding-bottom:10px;">' +
                    '<a href="#!">' +
                        '<img class="img-fluid" src="' + data.image + '" alt="" width="50">' +
                    '</a>' +
                    '<div class="media-body position-relative pl-3">' +
                        '<h6 class="fs-0 mb-0">' +
                            '<a href="#!">' + data.school +
                                '<small class="fas fa-check-circle text-primary ml-1" data-toggle="tooltip" data-placement="top" title="Verified" data-fa-transform="shrink-4 down-2"></small>' +
                            '</a>' +
                        '</h6>' +
                        '<p class="mb-1">' + data.sphere + '</p>' +
                        '<p class="text-1000 mb-0">' + data.degree + '</p>' +
                        '<p class="text-1000 mb-0">' + data.period + '</p>' +
                        '<p class="text-1000 mb-0">' + data.city + '</p>' +
                    '</div>' +
                '</div>';
    educationForm.insertAdjacentHTML('afterend', html);
}

/*--- Tomos Account Setting ---*/

if (settingForm = document.querySelector('#tomosAccountSetting')) {
    let inputs = settingForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    inputs.forEach((input) => {
        input.addEventListener('change', () => {
            let data = new FormData(settingForm);
            axios.post(settingForm.action, data)
                .then((response) => {
                    if (success = response.data.success) {
                        tomosSuccessMessage(success);
                    }
                })
                .catch((failure) => {
                    tomosDangerMessage();
                });
        });
    });
}

/*--- Tomos Account Password ---*/

if (passwordForm = document.querySelector('#tomosAccountPassword')) {
    passwordForm.addEventListener('submit', (event) => {
        event.preventDefault();
        let submitButton = passwordForm.querySelector('button[type="submit"]');
        let buttonText = submitButton.innerHTML;
        let data = new FormData(passwordForm);
        buttonLoadingStart(submitButton, buttonText);
        axios.post(passwordForm.action, data)
            .then((response) => {
                clearErrors();
                if (messages = response.data.messages) {
                    showValidationMessages(messages);
                } else if (success = response.data.success) {
                    tomosSuccessMessage(success);
                } else {
                    tomosSuccessMessage();
                }
            })
            .catch((failure) => {
                clearErrors();
                tomosDangerMessage();
            })
            .then(() => {
                let inputs = passwordForm.querySelectorAll('input[type="password"]');
                inputs.forEach((input) => input.value = '');
                buttonLoadingStop(submitButton, buttonText);
            });
      });
}
