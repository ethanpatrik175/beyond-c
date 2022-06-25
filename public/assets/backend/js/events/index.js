$(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: route('events.index'),
        columns: [{
            data: 'checkbox',
            name: 'checkbox'
        }, {
            data: 'id',
            name: 'id'
        }, {
            data: 'image',
            name: 'image'
        }, {
            data: 'titles',
            name: 'titles'
        }, {
            data: 'day_number',
            name: 'day_number'
        }, {
            data: 'start_at',
            name: 'start_at'
        }, {
            data: 'ticket_price',
            name: 'ticket_price'
        }, {
            data: 'created_at',
            name: 'created_at'
        }, {
            data: 'updated_at',
            name: 'updated_at'
        }, {
            data: 'status',
            name: 'status'
        }, {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }, ],
        responsive: true,
        'createdRow': function (row, data, dataIndex) {
            $(row).attr('id', data.id);
        },
        "order": [
            [0, "desc"]
        ],
        "bDestroy": true,
    });
});

let statusRoute = route('events.update.status');
$(document).on('click', '.status', function () {
    var id = $(this).attr('data-id');
    var status = $(this).val();
    $.ajax({
        type: 'POST',
        url: statusRoute,
        data: {
            id: id,
            status: status
        },
        success: function (data) {

            var result = JSON.parse(data);
            var type = result.type;

            if (status == 0) {
                $('.status#switch' + id).attr('value', 1);
            } else {
                $('.status#switch' + id).attr('value', 0);
            }
            $('.message').html('<div class="alert alert-' + result.type +
                ' alert-dismissible fade show" role="alert"><i class="mdi ' + result.icon +
                ' me-2"></i>' + result.message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'
            );
        }
    });

});

function deleteRecord(id)
{
    Swal.fire({
        title: "Are you sure?",
        text: "The record will be trashed!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#34c38f",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Yes, trash it!"
    }).then(function (t) {

        if (t.value) {
            $.ajax({
                type: 'DELETE',
                url: route('events.destroy', id),
                beforeSend: function () {
                    $.LoadingOverlay("show");
                },
                success: function (data) {
                    $.LoadingOverlay("hide");
                    var result = JSON.parse(data);
                    var type = result.type;
                    if(type == "success"){
                        Swal.fire("Trashed!", "Your data has been trashed.", "success")
                        $('.data-table').DataTable().ajax.reload();
                    }
                },
                error: function (data) {
                    $.LoadingOverlay("hide");
                    Swal.fire("Error!", "Your file has not been trashed.", "error")
                }
            });
        }
    })
}