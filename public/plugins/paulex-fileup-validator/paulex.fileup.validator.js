/* ===========================================================
 * Copyright Okeke Paul, Nigeria
 * Since 2015
 * Licenced Under VASCON-SOLUTIONS
 * ========================================================== */

/**
 * @author Paul Okeke
 * For validating file uploads on the client side
 * This method should be used to validate file uploads
 * so that the user is immediately prompted if there be any error
 *
 * For security issues we are validating the file type not by
 * its extension but its mime type
 * @param fz the maximum file size
 * <pre>
 *     The file size must be in a defined standard.
 *     e.g 1mb , 4gb, 2kb, 1b
 * </pre>
 * @param formats Array the file format is an array of mime types
 *        that the file input should accept
 *        <pre>
 *            Example: ['image/png', 'image/jpeg']
 *        </pre>
 * @param elemN String the file input name you want to validate
 *        <pre>
 *            Note:: the input must of type file
 *        </pre>
 * @param callback @Optional this callback function will be called if the file is properly validated
 *         <pre>
 *             The file Input element will be passed to this callback function
 *         </pre>
 *
 */
function validateFileUploads(fz, formats, elemN, callback)
{
    var fileElement = document.getElementsByName(elemN).item(0);
    if(fileElement!==null) {
        fileElement.addEventListener("change", function () {
            var fileSize = fileElement.files[0].size;
            var fileExt = fileElement.files[0].type;
            if(!checkFileSize(fz, fileSize)){
                alert("The uploaded file is larger than the required file size");
                resetFileInput(this);
                validateFileUploads(fz, formats, elemN, callback);
                return true;
            }
            if(!checkFileTypes(formats, fileExt)){
                alert("The uploaded file format isn't supported");
                resetFileInput(this);
                validateFileUploads(fz, formats, elemN, callback);
                return true;
            }
            if(typeof callback === 'function'){
                callback(fileElement);
            }
        });
    }
}

function resetFileInput(fileElement){
    if(fileElement.parentNode!==null) {
        fileElement.parentNode.replaceChild(fileElement.cloneNode(true), fileElement);
    }else{
        var f = document.getElementsByName(fileElement.name).item(0);
        f.parentNode.replaceChild(f.cloneNode(true), f);
    }
}

/**
 * @author Paul Okeke
 * Validates the file based on the fileLimit
 * @param fileLimit
 *   <pre>
 *     The file size must be in a defined standard.
 *     e.g 1mb , 4gb, 2kb, 1b
 * </pre>
 * @param inputSize
 * @returns {boolean}
 */
function checkFileSize(fileLimit, inputSize){
    var sizeType = fileLimit.replace(/[^A-Za-z]/g, "").toLocaleUpperCase();
    var sizeVal = parseInt(fileLimit.replace(/[^0-9]/g, ""));
    var byte = 0;
    switch (sizeType){
        case "KB":
            byte = Math.pow(2, 10);
            if(inputSize/ byte > sizeVal){
                return false;
            }
            break;
        case "MB":
            byte = Math.pow(2, 20);
            if(inputSize/ byte > sizeVal){
                return false;
            }
            break;
        case "GB":
            byte = Math.pow(2, 30);
            if(inputSize/ byte > sizeVal){
                return false;
            }
            break;
    }
    return true;
}

/**
 * @author Paul Okeke
 * @param formats
 * @param fileType
 * @returns {boolean}
 */
function checkFileTypes(formats, fileType){
    var match = false;
    if(formats instanceof Array) {
        formats.forEach(function (ele, index, i) {
            if (fileType.trim() === ele) {
                match = true;
            }
        });
    }else{
        throw new TypeError('formats must be an instance of Array');
    }
    return match;
}