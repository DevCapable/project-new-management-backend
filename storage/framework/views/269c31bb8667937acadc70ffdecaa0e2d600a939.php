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

    
        url: "<?php echo e(route('document_upload',$currentWorkspace->slug)); ?>",
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
<?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/projects/task/_client_fileUpload_js.blade.php ENDPATH**/ ?>