var drug = {
    selected: 0,
    id: 0,
    name: 'No drug',
    low: 0,
    high: 0,
    units: 'mg',
    unitsUp: 'g',
    unitsDown: 'mcg',
    
    showManualUnits:function()
    {
        $('#drug_units').hide('fast');
        $('#alt_units').show('fast');
    },

    showAutoUnits:function()
    {
        $('#drug_units').show('fast');
        $('#alt_units').hide('fast');
    },
    
    showEndDate:function()
    {
        $('#end_text').show('fast');
        $('#end_check').hide('fast');
        $('#end_text input').focus();
    },
    
    hideEndDate:function()
    {
        $('#end_text').hide('fast');
        $('#end_check').show('fast');
    },

    setCommonUnits:function(newUnits)
    {
        this.units = newUnits;
        $('#alt_drug_units select').val(newUnits);
        this.showAutoUnits();
        if ( newUnits == 'mg' )
        {
            this.unitsUp = 'g';
            this.unitsDown = 'mcg';
        }
        else if ( newUnits == 'g' )
        {
            this.unitsDown = 'mg';
        }
        else if ( newUnits == 'mcg' )
        {
            this.unitsUp = 'mg';
        }
    },

    adjustDose:function(dose)
    {
        mult = 1;
        if ( this.units == 'mg' )
            mult = 1000;
        if ( this.units == 'g' )
            mult = 1000000;
        
        return dose * mult;
    },

    correct:function(obj) {
        var value = obj.srcElement.value;
        var srcElement = obj.srcElement;
        var adjustedDose = this.adjustDose(value);
        if ( adjustedDose >= this.low && adjustedDose <= this.high )
        {
            // regular dose
            $('#alt_units select').val(this.units);
        }
        else if ( adjustedDose < this.low )
        {
            // low dose
            this.showManualUnits();
            $('#alt_units select').val(this.unitsUp);
        }
        else if ( adjustedDose > this.high )
        {
            // high dose
            this.showManualUnits();
            $('#alt_units select').val(this.unitsDown);
        }
    },

    getFormData:function() {
        return {
            drug_id: drug.id,
            units: $('#alt_units select').val(),
            dose: $('#drug_dose')[0].value,
            began: $('#drug_began')[0].value,
            ended: $('#drug_ended')[0].value,
        };
    },

    save:function(obj) {
        $.ajax({
            url: 'EnterDrugs/Save',
            type: 'POST',
            data: this.getFormData(),
            dataType: 'json',
            success:function(data, textStatus, jqXHR) {
                //alert(data);
                if ( data.result == 'success' )
                {
                    $('#history')[0].innerHTML = data.return_html;
                }
                else if ( data.result == 'error' )
                {
                    alert('Error on AJAX request: ' + data.message);
                }
            },
            error:function(jqXHR, textStatus, errorThrown) {
                alert(textStatus + errorThrown);
            }
        });
    }
};
