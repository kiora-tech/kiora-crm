import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { 'postalCodeCitiesUrl': String };
    static targets = ['postalCode', 'city', 'country'];

    postalCodeUpdate(event) {
        if (this.postalCodeTarget && this.cityTarget && this.countryTarget) {
            this.updateCityAndCountry(this.postalCodeTarget, this.cityTarget, this.countryTarget);
        }
    }

    updateCityAndCountry(postalCodeInput, cityInput, countryInput) {
        const postalCode = postalCodeInput.value;

        if (postalCode) {
            fetch(`${this.postalCodeCitiesUrlValue}?postal_code=${postalCode}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    let cityList = document.getElementById(cityInput.getAttribute('list'));
                    cityList.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(entry => {
                            const option = document.createElement('option');
                            option.value = entry.city;
                            cityList.appendChild(option);
                        });
                        countryInput.value = data[0].country;
                    } else {
                        const option = document.createElement('option');
                        option.value = 'Aucune ville trouvée';
                        cityList.appendChild(option);
                        countryInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des villes:', error);
                });
        }
    }
}
