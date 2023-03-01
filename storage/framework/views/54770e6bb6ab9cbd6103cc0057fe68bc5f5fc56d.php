<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="http://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="<?php echo e(asset('assets/js/dropify.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dropzone.js')); ?>"></script>
<script>


    var segments = location.href.split('/');
    var action = segments[3];
    // console.log(action);
    if (action === 'client') {
<?php echo $__env->make('projects.task._client_fileUpload_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    }
    var segments1 = location.href.split('/');
    var action1 = segments1[3];
if (action === 'admin') {
    var acceptedFileTypes = "image/*, .psd, .xls, .doc, .docx"; //dropzone requires this param be a comma separated list
    var fileList = [];
    var i = 0;
    var maxFilesize = 5;
    var callForDzReset = true;
    var _token = $("input[name='_token']").val()
    var task_id = $("input[name='task_id']")
        .map(function () {
            return $(this).val();
        }).get();


    $('#dropzonewidget')
        .dropzone({
            url: "<?php echo e(route('admin-document-upload',$currentWorkspace->slug)); ?>",
            headers: {
                'x-csrf-token': _token,
            },
            params: {
                'gallery_id': task_id
            },
            addRemoveLinks: false,
            maxFiles: 4,
            maxFilesize: maxFilesize,
            createImageThumbnails: true,
            maxThumbnailFilesize: 0.5,
            clickable: true,
            acceptedFiles: acceptedFileTypes,
            paramName: "files",
            dictDefaultMessage: '<i class="icon-plus"></i> <br>Drag files here or tap to <span>add files for <strong></strong></span> <br /> <span class="note"><small>Keep the file size below ' + maxFilesize +
                'MB (Max files/images 10)' + '<br /> Supported file types are ' + acceptedFileTypes + '</small></span>',
            init: function () {
                this.on("success", function (file, serverFileName) {

                    file.serverFn = serverFileName;
                    fileList[i] = {
                        "serverFileName": serverFileName,
                        "fileName": file.name,
                        "fileId": i
                    };
                    i++;
                    alert('File uploaded successfully')
                });
                this.on("removedfile", function (file) {
                    alert('Note that this file already deleted')
                    // for (var i = 0; i < fileList.length; i++) {
                    //     if (fileList[i].file_path === file.new_path) {
                    //         fileList.splice(i, 1);
                    //     }
                    // }
                });
            },

        });
}

</script>

<script type="text/javascript">

    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
    $(document).ready(function () {
        var html = '<table class="table  responsive"><tr><th colspan="3">Attachment</th></tr>' +
            '<tr><td colspan="3"><div class="dropzone" id="dropzone"><input hidden name="documents[]" id="documents" type="text" /></div></td>' +
            '</tr><tr><th colspan="3">Title</th></tr>' +
            '<tr><td colspan="3"><input class="form-control" type="text" name="title[]"></td></tr>' +
            '<tr><th colspan="1">Start Date</th><th colspan="2">Deadline</th></tr><tr>' +
            '<td colspan="1"><input type="date" class="form-control form-control-light" id="start_date" name="start_date[]" required autocomplete="off">' +
            '</td><td colspan="2"><input type="date" class="form-control form-control-light" id="due_date"  name="due_date[]" required autocomplete="off">' +
            '</td></tr><tr><th colspan="3">Description</th> </tr><tr><td colspan="3" rowspan="3"><textarea class="form-control form-control-light" id="description" rows="4" name="description[]"></textarea></td></tr>' +
            '<td colspan="1"><i class="fa fa-trash" style="font-size: 30px; color:red" name="remove" value="remove" id="remove"></i></td></tr></table';
        var max = 3;
        var x = 1;
        $('#add').click(function () {


            if (x < max) {
                $("#table_field").append(html);
                x++;
            } else if (x === max) {
                alert(`Sorry you can not add more than ` + max + ` fields at a time`)
            }
        })
        $('#table_field').on('click', '#remove', function () {
            $(this).closest('table').remove();
            x--;
        })

    })

</script>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/projects/task/_add_more_fields_js.blade.php ENDPATH**/ ?>