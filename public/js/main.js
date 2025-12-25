let Main = {
    validateform:(formid)=>{
        let status = true;
        var forms = document.getElementById(formid);
        // cek form input satu persatu
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (form.checkValidity() === false) {
                status = false;
                $(forms).find(".form-control:invalid").first().focus();
                $(forms).find(".form-check-input:invalid").first().focus();
                $('#'+formid).addClass('was-validated');
            }
        });
        return status;
    },
    inputnumberonly:(elm, e) =>{
        let value = $(elm).val()
        value = value.replace(/[^0-9]/g, '');
        $(elm).val(value);
    },
    formattercurrencycomma:(elm, max = null)=>{
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'decimal',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: max != null ? max : 20,
        });
        let input_val = $(elm).val();
        input_val = input_val.replace(/[^\d.-]/g, '');
        if (input_val != null && input_val != '') {
            input_val = input_val.replace(',','');
        }
        let value = formatter.format(input_val);
        $(elm).val(value);
    },
    formattercurrencyvalcomma:(value, max = null)=>{
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'decimal',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: max != null ? max : 20,
        });
        value = value.replace(/[^\d.-]/g, '');
        if (value != null && value != '') {
            value = value.replace(',','');
        }
        let valueformat = formatter.format(value);
        return valueformat;
    },
}