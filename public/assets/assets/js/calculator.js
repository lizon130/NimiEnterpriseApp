const seleted = () => {
    const option = parseInt(document.getElementById('option').value);
    let inputType = document.getElementById('inputType');
    const inputValue = document.getElementById('inputValue');
    const resultType = document.getElementById('resultType');
    const resultValue = document.getElementById('resultValue');
    if (option === 1) {
        inputType.innerText = 'Kilometers';
        resultType.innerText = 'Miles';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 2) {
        inputType.innerText = 'Kilograms';
        resultType.innerText = 'Pounds';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 3) {
        inputType.innerText = 'Litres';
        resultType.innerText = 'Gallons';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 4) {
        inputType.innerText = 'Miles';
        resultType.innerText = 'Kilometers';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 5) {
        inputType.innerText = 'Pounds';
        resultType.innerText = 'Kilograms';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 6) {
        inputType.innerText = 'Gallons';
        resultType.innerText = 'Liters';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 7) {
        inputType.innerText = 'Celsius';
        resultType.innerText = 'Fahrenheit';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 8) {
        inputType.innerText = 'Fahrenheit';
        resultType.innerText = 'Celsius';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 9) {
        inputType.innerText = 'Centimeters';
        resultType.innerText = 'Inches';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 10) {
        inputType.innerText = 'Inches';
        resultType.innerText = 'Centimeters';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 11) {
        inputType.innerText = 'Millimeters';
        resultType.innerText = 'Inches';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
    if (option === 12) {
        inputType.innerText = 'Inches';
        resultType.innerText = 'Millimeters';
        inputValue.value = 0.00;
        resultValue.value = 0.00;
    }
}

const converter = () => {
    console.log('clicked')
    const option = parseInt(document.getElementById('option').value);
    let inputType = document.getElementById('inputType');
    const inputValue = document.getElementById('inputValue');
    const resultType = document.getElementById('resultType');
    let resultValue = document.getElementById('resultValue');

    if(option === 1) {
        resultValue.value = inputValue.value * 0.62137119;
    }
    if(option === 2) {
        resultValue.value = inputValue.value * 2.20462262;
    }
    if(option === 3) {
        resultValue.value = inputValue.value *  0.264172052;
    }
    if(option === 4) {
        resultValue.value = inputValue.value * 1.60934;
    }
    if(option === 5) {
        resultValue.value = inputValue.value * 0.45359237;
    }
    if(option === 6) {
        resultValue.value = inputValue.value * 3.78541;
    }
    if(option === 7) {
        resultValue.value = (inputValue.value * 1.8) + 32;
    }
    if(option === 8) {
        resultValue.value = (inputValue.value - 32) / 1.8;
    }
    if(option === 9) {
        resultValue.value = inputValue.value * 0.393701;
    }
    if(option === 10) {
        resultValue.value = inputValue.value * 2.54;
    }
    if(option === 11) {
        resultValue.value = inputValue.value * 0.03937;
    }
    if(option === 12) {
        resultValue.value = inputValue.value * 25.4;
    }

}
