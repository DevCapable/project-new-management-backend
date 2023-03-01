"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    cache: false,
    complete: function () {
        LetterAvatar.transform();
        $('[data-toggle="tooltip"]').tooltip();
    },
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

 function show_toastr(title, message, type) {
        var o, i;
        var icon = '';
        var cls = '';
        if (type == 'success') {
            icon = 'fas fa-check-circle';
            // cls = 'success';
            cls = 'primary';
        } else {
            icon = 'fas fa-times-circle';
            cls = 'danger';
        }

        console.log(type,cls);
        $.notify({ icon: icon, title: " " + title, message: message, url: "" }, {
            element: "body",
            type: cls,
            allow_dismiss: !0,
            placement: {
                from: 'top',
                align: 'right'
            },
            offset: { x: 15, y: 15 },
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 2000,
            url_target: "_blank",
            mouse_over: !1,
            animate: { enter: o, exit: i },
            // danger
            template: '<div class="toast text-white bg-'+cls+' fade show" role="alert" aria-live="assertive" aria-atomic="true">'
                    +'<div class="d-flex">'
                        +'<div class="toast-body"> '+message+' </div>'
                        +'<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                    +'</div>'
                +'</div>'
            // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
        });
    }

$(document).ready(function () {
    $(window).resize();

    loadConfirm();

    // if ($("#selection-datatable").length) {
    //     $("#selection-datatable").DataTable({
    //         order: [],
    //         select: {style: "multi"},
    //         "language": dataTableLang,
    //         drawCallback: function () {
    //             $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    //         }
    //     });
    // }

    LetterAvatar.transform();
    $('[data-toggle="tooltip"]').tooltip();

    $('#commonModal-right').on('shown.bs.modal', function () {
        $(document).off('focusin.modal');
    });

    if ($(".summernote-simple").length) {
        $(".summernote-simple").summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [["style", ["bold", "italic", "underline", "clear"]], ["font", ["strikethrough"]], ["para", ["paragraph"]]]
        });
    }

    if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function () {
                    return "No result found";
                }
            },
        });
    }

    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });
});

// Common Modal
$(document).on('click', 'a[data-ajax-popup="true"],a[data_ajax_popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"], span[data-ajax-popup="true"]', function (e) {
   

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    if(url == null){
        var title = $(this).attr('data_title');
        var url = $(this).attr('data_url');
        var size = $(this).attr('data_size');
    }
    
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $("#commonModal .modal-footer").addClass('modal-footer');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
              $("#commonModal .modal-title").html(title);
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
    e.stopImmediatePropagation();
    return false;
});

// Common Modal from right side
$(document).on('click', 'a[data-ajax-popup-right="true"], button[data-ajax-popup-right="true"], div[data-ajax-popup-right="true"], span[data-ajax-popup-right="true"]', function (e) {
  
    var url = $(this).data('url');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $('#commonModal-right').html(data);
            $("#commonModal-right").modal('show');
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
});

function commonLoader() {

    LetterAvatar.transform();

    $('[data-toggle="tooltip"]').tooltip();

    if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function () {
                    return "No result found";
                }
            },
        });
    }


    if ($(".multi-select").length > 0) {
            $( $(".multi-select") ).each(function( index,element ) {
                var id = $(element).attr('id');
                   var multipleCancelButton = new Choices(
                        '#'+id, {
                            removeItemButton: true,
                           
                        }
                    );
            });
       }

           (function () {
        const d_week = new Datepicker(document.querySelector('#pc-datepicker-3'), {
            buttonClass: 'btn',
            todayBtn: true,
            clearBtn: true
        });
    })();

   

}

function loadConfirm() {
    // $('[data-confirm]').each(function () {
       
    //     var me = $(this),
    //         me_data = me.data('confirm');

    //     me_data = me_data.split("|");
    //     me.fireModal({
    //         title: me_data[0],
    //         body: me_data[1],
    //         buttons: [
    //             {
    //                 text: me.data('confirm-text-yes') || 'Yes',
    //                 class: 'btn btn-sm btn-danger rounded-pill',
    //                 handler: function () {
    //                     eval(me.data('confirm-yes'));
    //                 }
    //             },
    //             {
    //                 text: me.data('confirm-text-cancel') || 'Cancel',
    //                 class: 'btn btn-sm btn-secondary rounded-pill',
    //                 handler: function (modal) {
    //                     $.destroyModal(modal);
    //                     eval(me.data('confirm-no'));
    //                 }
    //             }
    //         ]
    //     })
    // });
}



// document.querySelector('.delete-popup').addEventListener("click", function () {
//     var id = $(this).data('id');
    
//     const swalWithBootstrapButtons = Swal.mixin({
//         customClass: {
//             confirmButton: 'btn btn-success',
//             cancelButton: 'btn btn-danger'
//         },
//         buttonsStyling: false
//     })
//     swalWithBootstrapButtons.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to revert this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Yes, delete it!',
//         cancelButtonText: 'No, cancel!',
//         reverseButtons: true
//     }).then((result) => {
//         if (result.isConfirmed) {
           
//   var id = $(this).data('id');
//           $('#delete-form-'+id).submit();
           
//          } 
        



//      })
// });


$(document).on("click", '.bs-pass-fn-call',function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: $(this).data('confirm'),
        text: $(this).data('text'),
        icon: 'warning',
        showCancelButton: true,
    confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: false,
    }).then((result) => {
        if (result.isConfirmed) {
            removeImage($(this).data('id'));     
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
        }
    })
});






function postAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: jdata,
        success: function(data) {
            if (typeof(data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

function deleteAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'DELETE',
        url: url,
        data: jdata,
        success: function(data) {
            if (typeof(data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}


$(document).on('click', '.fc-day-grid-event', function(e) {
    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title = $(this).find('.fc-content .fc-title').html();
    var size = 'md';
    var url = $(this).attr('href');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function(data) {
            $('#commonModal .modal-body').html(data);
            $("#commonModal").modal('show');
            common_bind();
        },
        error: function(data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});