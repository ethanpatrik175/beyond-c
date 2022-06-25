$(document).ready(function(){
    $(document).on('change', '#donation_amount', function(){
        if($(this).val() == "custom"){
            $('.custom_donation_amount').html('<input type="number" name="custom_amount" id="custom_amount" min="0" placeholder="Donation Amount ($)" class="mt-3" required/>');
        }
        else{
            $('.custom_donation_amount').html('');
        }
    });

    // $(document).on('change', '#payment_method', function(){
    //     if($(this).val() == "check")
    //     {
    //         $('.check_details').html('<textarea name="check_details" id="check_details" class="form-control mt-3" rows="6" placeholder="Check Details" required></textarea>');
    //     }
    //     else{
    //         $('.check_details').html('');
    //     }
    // });

    // $(document).load(function(){
    //     $.LoadingOverlay("show");
    // });

    // $(document).on('submit', '#charity_form', function(event){
    //     event.preventDefault();
    //     $.ajax({
    //         url: $(this).attr('action'),
    //         type: 'POST',
    //         data: $(this).serialize(),
    //         beforeSend: function(){
    //             $.LoadingOverlay("show");
    //         },
    //         success: function(response){
    //             $.LoadingOverlay("hide");
    //             let result = JSON.parse(response);
    //             if(result.type == "success")
    //             {
    //                 window.location.href=route('thank.you', {":type":'success', ":message":'test message'});
    //             }                
    //         },
    //         error: function(response){
    //             $.LoadingOverlay("hide");
    //             console.log(response);
    //         }
    //     });
    // });
});