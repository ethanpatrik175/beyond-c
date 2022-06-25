$(document).on('keyup', '#name', function(){
    $.ajax({
        url: route('create.slug'),
        type: 'POST',
        data: {'name': $(this).val()},
        success: function(response){
            $('#slug').val(response);
        },
        error: function(error){
            console.log(error);
        }
    });
});