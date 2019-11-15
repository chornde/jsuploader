/**
    todo:
    - show upload area
    - distribute events
    - check max size and file types
    - check free slots; reserve
    - preview in the next free slot
    - progress bar in the next free slot
    - disable inputs on upload
    - reload page after all images are uploaded
    - add buttons for default, delete, rotate

    mostly based on https://www.sitepoint.com/html5-javascript-file-upload-progress-bar/
 */
let uploader = {

    init: function(){
        uploader.element = document.getElementById('uploadElement');
        uploader.submitter = document.getElementById('uploadSubmit');
        uploader.area = document.getElementById('uploadArea');
        uploader.adaptListeners(uploader.submitter, uploader.element, uploader.area);
        console.log(uploader.freeSlots());
    },

    dragVisual: function(e) {
        e.stopPropagation();
        e.preventDefault();
        e.target.className = (e.type === 'dragover' ? 'hover' : '');
    },

    adaptListeners: function(submitter, element, area){
        element.addEventListener('change', uploader.prepareUpload, false);
        submitter.style.display = 'none';

        let xhr = new XMLHttpRequest();
        if (xhr.upload) {
            area.style.display = 'block';
            area.addEventListener('dragover', uploader.dragVisual, false);
            area.addEventListener('dragleave', uploader.dragVisual, false);
            area.addEventListener('drop', uploader.prepareUpload, false);
        }

        uploader.getSlots().forEach(slot => {
            slot.querySelector('a').onclick = function(){
                uploader.element.click();
            };
        });
    },

    // always fresh
    getSlots: function(){
        return [...document.querySelectorAll('.uploadSlot')];
    },

    freeSlots: function(){
        return uploader.getSlots().filter(function(slot){ return !slot.classList.contains('reserved'); });
    },

    showPreview: function(slot, file){
        let image = slot.querySelector('img');
        reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
        };
        reader.readAsDataURL(file);
    },

    prepareUpload: function(e){
        uploader.dragVisual(e);
        let files = e.target.files || e.dataTransfer.files;
        [...files].forEach(file => {
            let freeSlots = uploader.freeSlots();
            if(freeSlots.length > 0 && file.type.indexOf("image") === 0){
                let freeSlot = freeSlots[0];
                freeSlot.classList.add('reserved');
                uploader.showPreview(freeSlot, file);
                uploader.doUpload(file, freeSlot);
            }
        });
    },

    showProgress: function(xhr, slot){
        let progress = slot.querySelector('p');
        xhr.upload.addEventListener("progress", function(e) {
            let pc = parseInt(e.loaded / e.total * 100);
            progress.style.backgroundPosition = pc + "% 0";
        }, false);
        xhr.onreadystatechange = function(e) {
            if (xhr.readyState === 4) {
                progress.className = (xhr.status === 200 ? "success" : "failure");
            }
        };
    },

    doUpload: function(file, slot){
        var xhr = new XMLHttpRequest();
        uploader.showProgress(xhr, slot);
        xhr.open("POST", document.getElementById("upload").action, true);
        xhr.setRequestHeader("X_FILENAME", file.name);
        xhr.send(file);
    },

};