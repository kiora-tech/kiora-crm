import { Controller } from '@hotwired/stimulus';
import intlTelInput from 'intl-tel-input/build/js/intlTelInput.min.js';
import 'intl-tel-input/build/js/utils.js';

export default class extends Controller {
    static targets = ['phone'];

    #iti = null;

    connect() {
        if (this.hasPhoneTarget) {
            this.#iti = intlTelInput(this.phoneTarget, {
                initialCountry: "auto",
                nationalMode: false,
                geoIpLookup: (success, failure) => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => success(data.country_code))
                        .catch(() => failure());
                },
                utilsScript: 'intl-tel-input/build/js/utils.js'
            });
        }
    }

    phoneUpdate(event) {
        if (this.hasPhoneTarget) {
            this.phoneTarget.value = this.#iti.getNumber();
        }
    }
}
