$(document).ready(function(){
    $('#upload_trigger').click(function(e){
        e.prevetDefault;
        $(this).next('input[type=file]').trigger('click');
    });
});