$(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: route('orders.index'),
        columns: [{
            data: 'checkbox',
            name: 'checkbox'
        }, {
            data: 'id',
            name: 'id'
        }, {
            data: 'order',
            name: 'order'
        }, {
            data: 'name',
            name: 'name'
        }, {
            data: 'order_status',
            name: 'order_status'
        }, {
            data: 'total',
            name: 'total'
        },{
            data: 'created_at',
            name: 'created_at'
        }, {
            data: 'updated_at',
            name: 'updated_at'
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
                url: route('speakers.destroy', id),
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