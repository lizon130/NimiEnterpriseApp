<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calculator</title>
    <script src="{{ asset('assets/js/calculator.js') }}"></script>
    <style>
        .calculator{
            display: grid;
            grid-template-columns: 1fr 3fr;
            align-items: center;
            row-gap: 20px;
            font-size: 20px;
        }
        #option, #inputValue, #resultValue{
            box-sizing: border-box;
            width: 100%;
            height: 40px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <div>Select</div>
        <div>
            <select name="" id="option" onchange="seleted()">
                <option value="1">Kilometers to Miles</option>
                <option value="2">Kilograms to Pounds</option>
                <option value="3">Litres to Gallons</option>
                <option value="4">Miles to Kilometers</option>
                <option value="5">Pounds to Kilograms</option>
                <option value="6">Gallons to Liters</option>
                <option value="7">Celsius to Fahrenheit</option>
                <option value="8">Fahrenheit to Celsius</option>
                <option value="9">Centimeters to Inches</option>
                <option value="10">Inches to Centimeters</option>
                <option value="11">Millimeters to Inches</option>
                <option value="12">Inches to Millimeters</option>
            </select>
        </div>
        <div id="inputType">Kilometers</div>
        <div>
            <input oninput="converter()" type="text" value="0" id="inputValue">
        </div>
        <div id="resultType">Miles</div>
        <div id="">
            <input type="text" value="0" id="resultValue">
        </div>
    </div>
</body>
</html>
