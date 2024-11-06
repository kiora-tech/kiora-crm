import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "maritalSituation", "spouse" ]

    connect() {
        this.toggleSpouseField();
    }

    toggleSpouseField() {
        const maritalStatus = this.maritalSituationTarget.value;
        if (maritalStatus === 'M' || maritalStatus === 'P') {
            this.spouseTarget.style.display = 'block';
        } else {
            this.spouseTarget.style.display = 'none';
        }
    }

    change(event) {
        this.toggleSpouseField();
    }
}