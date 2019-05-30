
const SPINNER = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';

const bindImageControlsEvent = () => {
    if (imageCardControls = document.querySelectorAll('.image-card')) {
        var editimgButton = document.getElementById('editimgButton');
        var inputsPanel = document.getElementById('editimgInputs');
        var inputTitle = document.getElementById('imgeditTitle');
        var inputText = document.getElementById('imgeditText');
        var $editimgModal = $('#editimgModal');
        var displayTitle;
        var displayText;
        var editAction;
        var mediaLayer = document.getElementById('mediaLayer');
        var melClose = document.querySelector(".mel-close");
        imageCardControls.forEach((item) => {
            var action = item.getAttribute('data-action');
            var input  = item.querySelector('input[name="enabled"]');
            var label  = item.querySelector('label[for="' + input.id + '"]');
            input.addEventListener('change', () => {
                var data = new FormData();
                data.append('form_type', 'enabled');
                data.append('enabled', input.checked ? '1' : '0');
                axios.post(action, data)
                    .then((response) => {
                        if (success = response.data.success) {
                            label.innerText = success;
                        }
                    })
                    .catch((failure) => {
                        alert('Fail to chenge image status...');
                    });
            });
            var closeButton = item.querySelector('.close');
            closeButton.addEventListener('click', () => {
                if (! confirm("The image will be deleted!")) {
                    return;
                }
                var data = new FormData();
                data.append('form_type', 'delete');
                axios.post(action, data)
                    .then((response) => {
                        if (success = response.data.success) {
                            changeImgCounter('decrement');
                            item.style.display = 'none';
                        }
                    })
                    .catch((failure) => {
                        alert('Fail to delete image...');
                    });
            });
            var editTitles = item.querySelector('.image-titles');
            editTitles.addEventListener('click', () => {
                editAction   = action;
                displayTitle = item.querySelector('.image-title');
                displayText  = item.querySelector('.image-text');
                $editimgModal.on('show.bs.modal', () => {
                	clearModalFormMessages();
                    inputTitle.value = displayTitle.innerText;
                    inputText.value = displayText.innerText;
                }).on('hidden.bs.modal', () => {
                    $(this).data('bs.modal', null);
                });
                $editimgModal.modal('show');
            });
            var imageLink  = item.querySelector('.image-link');
            var itemTitle  = item.querySelector('.image-title');
            var itemText   = item.querySelector('.image-text');
            var melImage   = mediaLayer.querySelector('.mel-image');
            var melTitle = mediaLayer.querySelector('.mel-title');
            var melText  = mediaLayer.querySelector('.mel-text');
            imageLink.addEventListener('click', (e) => {
                e.preventDefault();
                document.body.classList.add('no-scrolling');
                mediaLayer.style.display = 'block';
                melImage.src = imageLink.href;
                melTitle.innerText = itemTitle.innerText;
                melText.innerText  = itemText.innerText;
            });
        });
        editimgButton.addEventListener('click', () => {
            var hideModal  = false;
            var buttonText = editimgButton.innerHTML;
            editimgButton.setAttribute('disabled', 'disabled');
            editimgButton.innerHTML = SPINNER + buttonText;
            var data = new FormData();
            data.append('form_type', 'edit-titles');
            data.append('title', inputTitle.value);
            data.append('text', inputText.value);
            axios.post(editAction, data)
                .then((response) => {
                    if (success = response.data.success) {
                        displayTitle.innerText = success.title;
                        displayText.innerText = success.text
                            .replace(/\s?(<br\s?\/?>)\s?/g, "\r\n");
                        hideModal = true;
                    } else {
                        modalEditImgMessage('Failed to upload image');
                    }
                })
                .catch((failure) => {
                    if (messages = failure.response.data.messages) {
                        modalEditImgMessage(messages);
                    } else {
                        modalEditImgMessage();
                    }
                })
                .then(() => {
                    editimgButton.innerHTML = buttonText;
                    editimgButton.removeAttribute("disabled");
                    if (hideModal === true) {
                        $editimgModal.modal('hide');
                    }
                });
        });
        melClose.addEventListener('click', () => {
            mediaLayer.style.display = 'none';
            document.body.classList.remove('no-scrolling');
        });
        document.addEventListener('keydown', (event) => {
            event = event || window.event;
            if (event.keyCode === 27) {
                mediaLayer.style.display = 'none';
                document.body.classList.remove('no-scrolling');
            }
        });
    }
};

if (tomosAddImage = document.getElementById('tomosAddImage')) {
    var newimgImage  = document.getElementById('newimgImage');
    var newimgFile   = document.getElementById('newimgFile');
    var inputsPanel  = document.getElementById('inputsPanel');
    var newimgButton = document.getElementById('newimgButton');
    var newimgLabel  = document.querySelector('label[for="newimgFile"]');
    var newimgForm   = document.getElementById('newimgForm');
    var modeSwitcher = document.getElementById('mode-switcher');
    var $newimgModal = $('#newimgModal');
    var imageCropper;
    var mode;
    var land = {
        minWidth:      640,
        minHeight:     360,
        cropperWidth:  1920,
        cropperHeight: 1080,
        aspectRatio:   16/9,
        canvasWidth:   374,
        canvasHeight:  210
    };
    var port = {
        minWidth:      360,
        minHeight:     540,
        cropperWidth:  720,
        cropperHeight: 1080,
        aspectRatio:   4/6,
        canvasWidth:   242,
        canvasHeight:  363
    };
    newimgFile.addEventListener('change', (event) => {
        mode = modeSwitcher.checked ? port : land;
        var newimgFiles = event.target.files;
        var done = (url) => {
            newimgFile.value = '';
            newimgImage.src = url;
            imageCropper = new Cropper(newimgImage, {
                aspectRatio: mode.aspectRatio,
                viewMode: 3,
                data: {
                    width:  mode.cropperWidth,
                    height: mode.cropperHeight,
                },
                crop: (event) => {
                    var width  = event.detail.width;
                    var height = event.detail.height;
                    if (width < mode.minWidth || height < mode.minHeight) {
                        imageCropper.setData({
                            width: Math.max(mode.minWidth, width),
                            height: Math.max(mode.minHeight, height),
                        });
                    }
                },
            });
        };
        var newimgReader;
        var newimgFile;
        if (newimgFiles && newimgFiles.length > 0) {
            newimgFile = newimgFiles[0];
            if (URL) {
                done(URL.createObjectURL(newimgFile));
            } else if (FileReader) {
                newimgReader = new FileReader();
                newimgReader.onload = () => {
                    done(newimgReader.result);
                };
                newimgReader.readAsDataURL(newimgFile);
            }
        }
        newimgButton.removeAttribute('disabled');
        newimgLabel.parentNode.style.display = 'none';
        inputsPanel.classList.remove('d-none');
    });
    $newimgModal.on('hidden.bs.modal', () => {
        clearModalFormMessages();
        newimgForm.querySelector('#text').value = '';
        newimgForm.querySelector('#title').value = '';
        newimgButton.setAttribute('disabled', "disabled");
        newimgLabel.parentNode.style.display = '';
        inputsPanel.classList.add('d-none');
        modeSwitcher.checked = false;
        imageCropper.destroy();
        newimgFile.value = '';
        newimgImage.src  = '';
        imageCropper = null;
    });
    newimgButton.addEventListener('click', () => {
        if (imageCropper) {
            var canvas = imageCropper.getCroppedCanvas({
                fillColor: '#ffffff',
                width:  mode.canvasWidth,
                height: mode.canvasHeight
            });
            var buttonText = newimgButton.innerHTML;
            newimgButton.setAttribute('disabled', "disabled");
            newimgButton.innerHTML = SPINNER + buttonText;
            canvas.toBlob((blob) => {
                var formData = new FormData(newimgForm);
                formData.append('cropped', blob, 'cropped.jpg');
                formData.append('original', newimgFile.files[0]);
                formData.append('orient', modeSwitcher.checked ? 'port' : 'land');
                axios.post(newimgForm.action, formData)
                    .then((response) => {
                        if (url = response.data.success.url) {
                            window.location.href = url;
                        } else {
                            modalNewImgMessage('Failed to upload image...');
                        }
                    })
                    .catch((failure) => {
                        if (messages = failure.response.data.messages) {
                            modalNewImgMessage(messages);
                        } else {
                            modalNewImgMessage();
                        }
                    })
                    .then(() => {
                        newimgButton.innerHTML = buttonText;
                        newimgButton.removeAttribute("disabled");
                    });
            }, 'image/jpeg', 0.8);
        }
    });
    bindImageControlsEvent();
}

const changeImgCounter = (type) => {
    type = type || 'increment';
    var counter = document.getElementById('img-counter');
    var current = parseInt(counter.innerText);
    counter.innerText = type == 'increment' ? current + 1 : current - 1;
};

const clearModalFormMessages = () => {
    if (dangerMessages = document.querySelectorAll('.alert-danger')) {
        dangerMessages.forEach((message) => {
            message.remove();
        })
    }
};

const modalEditImgMessage = (messages) => {
    message = messages || 'Unknown Server Error';
    if (typeof message === 'object') {
        var firstKey = Object.keys(messages)[0];
        message = message[firstKey];
    }
    clearModalFormMessages();
    var alerts = document.getElementById('editimgInputs');
    var html = '<div class="alert alert-danger alert-dismissible fade show clearfix" role="alert">' +
                   '<div class="float-left align-middle fs-1"><i class="fas fa-exclamation-triangle"></i></div>' +
                   '<div class="col-11 float-right pl-1 fs--1">' + message + '</div>' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('afterbegin', html);
};

const modalNewImgMessage = (messages) => {
    message = messages || 'Unknown Server Error';
    if (typeof message === 'object') {
        var firstKey = Object.keys(messages)[0];
        message = message[firstKey];
    }
    clearModalFormMessages();
    var alerts = document.getElementById('inputsPanel');
    var html = '<div class="alert alert-danger alert-dismissible fade show clearfix" role="alert">' +
                   '<div class="float-left align-middle fs-1"><i class="fas fa-exclamation-triangle"></i></div>' +
                   '<div class="col-11 float-right pl-1 fs--1">' + message + '</div>' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('afterbegin', html);
};
