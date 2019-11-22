/**
    todo:
    + show upload area
    + distribute events
    + check free slots; reserve
    + check max size and file types
    + preview in the next free slot
    + progress bar in the next free slot
    X disable inputs on upload; no, why?
    - reload page after all images are uploaded
    - add buttons for default, delete, rotate

    inspired by https://www.sitepoint.com/html5-javascript-file-upload-progress-bar/
 */
let uploader = {

    fileTypes: ['image/jpeg', 'image/png'],
    fileMaxsize: 8000000,
    currentUploads: 0,

    init: function(){
        uploader.form = document.getElementById('uploadForm');
        uploader.element = document.getElementById('uploadElement');
        uploader.submitter = document.getElementById('uploadSubmit');
        uploader.area = document.getElementById('uploadArea');
        uploader.adaptListeners(uploader.submitter, uploader.element, uploader.area);
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
            element.style.display = 'none';
            area.style.display = 'block';
            area.addEventListener('click', function(){ uploader.element.click(); }, false);
            area.addEventListener('dragover', uploader.dragVisual, false);
            area.addEventListener('dragleave', uploader.dragVisual, false);
            area.addEventListener('drop', uploader.prepareUpload, false);
        }

        uploader.getSlots().forEach(slot => {
            slot.onclick = function(){
                uploader.element.click();
            };
        });
    },

    // always fresh
    getSlots: function(){
        slots = [...document.querySelectorAll('.uploadSlot')];
        slots.forEach(function(slot, idx){
            slot.setAttribute('data-index', idx);
        });
        return slots;
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
        [...files].forEach(function(file, idx){
            let freeSlots = uploader.freeSlots();
            if(freeSlots.length > 0){
                if(file.type.indexOf('image') === 0){
                    if(uploader.fileTypes.indexOf(file.type) >= 0){
                        if(file.size <= uploader.fileMaxsize){
                            let freeSlot = freeSlots[0];
                            freeSlot.classList.add('reserved');
                            // uploader.showPreview(freeSlot, file);
                            uploader.doUpload(file, freeSlot, freeSlot.getAttribute('data-index'));
                            uploader.currentUploads++;
                        }
                        else { alert('Die Datei ist zu groß: '+file.name); }
                    }
                    else { alert('Die Datei ist nicht kompatibel: '+file.type); }
                }
                else { alert('Diese Datei ist kein Bild: '+file.name); }
            }
            else { alert('Die maximale Anzahl an Bildern ist erreicht: '+file.name); }
        });
    },

    showProgress: function(xhr, slot){
        let progress = slot.querySelector('p.progress');
        xhr.upload.addEventListener('progress', function(e) {
            let pc = parseInt(e.loaded / e.total * 100);
            progress.style.width = pc + '%';
        }, false);
        xhr.onreadystatechange = function(e) {
            if (xhr.readyState === 4) {
                progress.classList.add(xhr.status === 200 ? 'success' : 'failure');
                uploader.currentUploads--;
                uploader.performReady();
            }
        };
    },

    performReady: function(){
        if(uploader.currentUploads === 0) setTimeout(function(){ window.location.reload(true); }, 1000);
    },

    doUpload: function(file, slot, idx){
        let formData = new FormData;
        formData.append('action', 'add');
        formData.append('slot', idx);
        formData.append('images[]', file, file.name);
        let xhr = new XMLHttpRequest();
        uploader.showProgress(xhr, slot);
        xhr.open('POST', uploader.form.action, true);
        xhr.send(formData);
    },

};