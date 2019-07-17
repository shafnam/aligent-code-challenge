$(document).ready(function() {
    //$('body').bootstrapMaterialDesign();
    
    $('.datetimepicker_start').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
        //format: 'YYYY-MM-DD hh:mm'
    });

    $('.datetimepicker_end').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
        //format: 'YYYY-MM-DD hh:mm'
    });

});

$(document).ready(function(){
    $('#allow_timezone').change(function(){
        if (this.checked) {
            $('#timezone').show();
        }
        else {
            $('#timezone').hide();
        }                   
    });
    if($('.timezonev input[type="checkbox"]:checked').length !== 0){
        //alert('one or more checked');
        $('#timezone').show();
    }else{
        //alert('Nothing checked');
        $('#timezone').hide();
    }
});