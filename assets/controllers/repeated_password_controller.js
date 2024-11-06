import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "firstInput", "firstPasswordLength", "firstPasswordMinNbSpecial", "firstPasswordMinNbUpper", "firstPasswordMinNbLower", "firstPasswordMinNbDigit",
        "secondInput", "secondPasswordSame"
    ]

    static values = {
        length: Number,
        minNbSpecial: Number,
        minNbUpper: Number,
        minNbLower: Number,
        minNbDigit: Number,
        checked: String,
        notChecked: String
    }

    connect() {
        this.firstPasswordLengthTarget.innerHTML = this.notCheckedValue;
        this.firstPasswordMinNbSpecialTarget.innerHTML = this.notCheckedValue;
        this.firstPasswordMinNbUpperTarget.innerHTML = this.notCheckedValue;
        this.firstPasswordMinNbLowerTarget.innerHTML = this.notCheckedValue;
        this.firstPasswordMinNbDigitTarget.innerHTML = this.notCheckedValue;
        this.secondPasswordSameTarget.innerHTML = this.notCheckedValue;
    }

    changedFirst() {
        const password = this.firstInputTarget.value;

        const length = password.length;
        const minNbSpecial = password.match(/[^A-Za-z0-9]/g)?.length ?? 0;
        const minNbUpper = password.match(/[A-Z]/g)?.length ?? 0;
        const minNbLower = password.match(/[a-z]/g)?.length ?? 0;
        const minNbDigit = password.match(/[0-9]/g)?.length ?? 0;

        this.firstPasswordLengthTarget.innerHTML = length >= this.lengthValue ? this.checkedValue : this.notCheckedValue;
        this.firstPasswordMinNbSpecialTarget.innerHTML = minNbSpecial >= this.minNbSpecialValue ? this.checkedValue : this.notCheckedValue;
        this.firstPasswordMinNbUpperTarget.innerHTML = minNbUpper >= this.minNbUpperValue ? this.checkedValue : this.notCheckedValue;
        this.firstPasswordMinNbLowerTarget.innerHTML = minNbLower >= this.minNbLowerValue ? this.checkedValue : this.notCheckedValue;
        this.firstPasswordMinNbDigitTarget.innerHTML = minNbDigit >= this.minNbDigitValue ? this.checkedValue : this.notCheckedValue;

        this.changedSecond();
    }

    changedSecond() {
        const firstPassword = this.firstInputTarget.value;
        const secondPassword = this.secondInputTarget.value;

        const same = firstPassword !== "" && firstPassword === secondPassword;

        this.secondPasswordSameTarget.innerHTML = same ? this.checkedValue : this.notCheckedValue;
    }
}