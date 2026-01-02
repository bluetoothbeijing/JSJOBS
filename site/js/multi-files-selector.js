// global variable to check total size of selected files and upcoming selections
var contentLength = 0;

function addNewResumeFile(inputId, postMaxSize, memoryLimit, maxFiles, maxSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath) {
    var totalSelectedFiles = jQuery("div#selectedFiles").children("span").length;
    var totalUploadedFiles = jQuery("div#existingFiles").children("span").length;
    var totalInputs = jQuery("div#fileSelectionButton").children("input").length;
    if (totalInputs == 1 || totalInputs < 1 || totalInputs == 0) {
        contentLength = 0;
    }
    if (totalSelectedFiles == 0 && totalUploadedFiles == 0) {
        jQuery("#selectedFiles").html('');
    }
    var files = document.getElementById("resumefiles_" + inputId).files;
    var filesLength = files.length;
    var contentSize = 0;
    for (var i = 0; i < filesLength; i++) {
        contentSize = contentSize + files[i].size;
    }
    contentLength = contentLength + contentSize;
    var fileExtMisMatch = '';
    var fileSizeExceeded = '';
    var allowedExtArray = fileTypes.split(',');

    // Check for the total size of selected files data.
    if ((postMaxSize > 0 && contentLength > (postMaxSize * 1024 * 1024))) {
        contentLength = contentLength - contentSize;
        deleteResumeSelections(inputId);
        addNewResumeInput(postMaxSize, memoryLimit, maxFiles, maxSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath);
        alert(sizeExceedLang);
        return false;
    } else {
        contentLength = 0;
    }
    if (totalSelectedFiles < maxFiles && totalUploadedFiles < maxFiles) {
        if (files.length > 0) {
            jQuery("#filesInfo").append('<div id="chosenFiles_' + inputId + '" class="chosenFiles js-row no-margin"><div class="hoverLayer"><span id="deleteResumeSelections" onclick="return deleteResumeSelections(' + inputId + ');" class="deleteChosenFiles"><img src="' + juriPath + 'components/com_jsjobs/images/trash.png" />' + clearFilesLang + '</span></div>');
            for (var i = 0; i < filesLength; i++) {

                var fileError = 0;
                var fileExt = files[i].name.split('.').pop().toLowerCase();
                var fileSize = (files[i].size / 1024);

                var fileBoxClass = 'chosenFile';
                var error = '<span class="errorText">' + fileRejLang + '</span>';

                if (jQuery.inArray(fileExt, allowedExtArray) < 0) {
                    var fileBoxClass = 'chosenErrorFile';
                    fileError = 1;
                    fileExtMisMatch = '<div class="fileUploadError js-col-lg-12 js-col-md-12 no-padding"><span class="errorHead">' + errorLang + ': </span><span class="errorText">' + error + '</span><span class="errorText"> ' + extMissLang + '</span>';
                    error = '';
                }
                if (fileSize > maxSize) {
                    var fileBoxClass = 'chosenErrorFile';
                    fileError = 1;
                    if (error == '') {
                        fileSizeExceeded = '<span class="errorText"> ' + sizeExceedLang + '</span></div>';
                    }
                    else {
                        fileSizeExceeded = '<div class="fileUploadError js-col-lg-12 js-col-md-12 no-padding"><span class="errorHead">Error: </span><span class="errorText">' + error + '</span><span class="errorText"> ' + andSizeExceedLang + '</span></div>';
                    }
                }
                if (fileError == 0) {
                    totalSelectedFiles = jQuery("div#selectedFiles").children("span").length;
                    totalUploadedFiles = jQuery("div#uploadedFiles").children("span").length;
                    if (totalSelectedFiles < maxFiles && totalUploadedFiles < maxFiles) {
                        var selectedFilename = files[i].name.substring(0, 4);
                        jQuery("#selectedFiles").append('<span class="selectedFile filesgroup_' + inputId + '">' + selectedFilename + '.. .' + fileExt + '</span>');
                    } else {
                        var fileBoxClass = 'chosenErrorFile';
                        deleteResumeSelections(inputId);
                        alert(filesLimitExceedLang);
                        break;
                    }
                }

                jQuery("#chosenFiles_" + inputId).append('<div class="' + fileBoxClass + ' js-row no-margin"><div class="js-col-lg-12 js-col-md-12 no-padding"><div class="js-row no-margin"><span class="uploadFileName">' + files[i].name + '</span></div>' + fileExtMisMatch + fileSizeExceeded + '</div></div>');

                fileExtMisMatch = '';
                fileSizeExceeded = '';
            }
            var hoverLayerHeight = jQuery("#chosenFiles_" + inputId).height();
            var hoverLayerWidth = jQuery("#chosenFiles_" + inputId).width();
            var deleteButtonHeight = jQuery("#deleteResumeSelections").height();
            var deleteButtonWidth = jQuery("#deleteResumeSelections").width();
            jQuery("#chosenFiles_" + inputId + " div.hoverLayer").height(hoverLayerHeight - 1);
            jQuery("#chosenFiles_" + inputId + " div.hoverLayer").hover(function () {
                jQuery("#chosenFiles_" + inputId + " div.hoverLayer span").css("top", "" + hoverLayerHeight / 2 + "px");
                jQuery("#chosenFiles_" + inputId + " div.hoverLayer span").css("left", "240px");
            });

            addNewResumeInput(postMaxSize, memoryLimit, maxFiles, maxSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath);
            totalSelectedFiles = jQuery("div#selectedFiles").children("span").length;
            totalUploadedFiles = jQuery("div#uploadedFiles").children("span").length;
            if (totalSelectedFiles == 0) {
                jQuery("#selectedFiles").html(noFileLang);
            }
            if (totalUploadedFiles == 0) {
                jQuery("#uploadedFiles").html(noFileLang);
            }
            jQuery("#filesInfo").append('</div>');
        } else {
        }
    } else {
        alert(filesLimitExceedLang);
        return;
    }
}

function addNewResumeInput(postMaxSize, memoryLimit, maxFiles, maxSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath) {

    hiddenInputs = jQuery("#fileSelectionButton").children("input:hidden").length;
    totalInputs = jQuery("#fileSelectionButton").children("input").length + hiddenInputs;
    jQuery("#fileSelectionButton input").each(function () {
        jQuery(this).css('display', 'none');
    });
    jQuery("#fileSelectionButton span").each(function () {
        jQuery(this).css('display', 'none');
    });
    var newInputId = Math.floor((Math.random() * 100) + 1);

    jQuery("#fileSelectionButton").prepend('<input type="file" class="resumefiles" name="resumefiles[]" onchange="addNewResumeFile(' + newInputId + ', ' + postMaxSize + ', ' + memoryLimit + ', ' + maxFiles + ', ' + maxSize + ', \'' + fileTypes + '\', \'' + fileRejLang + '\', \'' + clearFilesLang + '\', \'' + errorLang + '\', \'' + extMissLang + '\', \'' + sizeExceedLang + '\', \'' + andSizeExceedLang + '\', \'' + filesLimitExceedLang + '\', \'' + noFileLang + '\', \'' + juriPath + '\');" id="resumefiles_' + newInputId + '"  multiple="multiple" /><span id="fileSelectorText_' + newInputId + '" onclick="return addNewResumeFile(' + newInputId + ', ' + postMaxSize + ', ' + memoryLimit + ', ' + maxFiles + ', ' + maxSize + ', \'' + fileTypes + '\', \'' + fileRejLang + '\', \'' + clearFilesLang + '\', \'' + errorLang + '\', \'' + extMissLang + '\', \'' + sizeExceedLang + '\', \'' + andSizeExceedLang + '\', \'' + filesLimitExceedLang + '\', \'' + noFileLang + '\', \'' + juriPath + '\');" class="fileSelector">'+Joomla.Text._('Select Files')+'</span>');
    jQuery("#resumefiles_" + newInputId).css('display', 'block');
    jQuery("#fileSelectorText_" + newInputId).css('display', 'inline-block');

    return newInputId;
}
function deleteResumeSelections(inputId) {
    var parent = document.getElementById("fileSelectionButton");
    var childInput = document.getElementById("resumefiles_" + inputId);
    var childSpan = document.getElementById("fileSelectorText_" + inputId);
    jQuery("#resumefiles_" + inputId).remove();
    jQuery("#fileSelectorText_" + inputId).remove();
    jQuery("#chosenFiles_" + inputId).remove();
    jQuery("#selectedFiles span.filesgroup_" + inputId).each(function () {
        jQuery(this).remove();
    });
}
