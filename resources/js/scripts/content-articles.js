


/*--- Tomos Content Articles ---*/

const SPINNER = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';

const COVER_OPTIONS = {
    minWidth:      752,
    minHeight:     423,
    cropperWidth:  752,
    cropperHeight: 423,
    aspectRatio:   16/9,
    canvasWidth:   752,
    canvasHeight:  423
};

const bindArticleControlsEvent = () => {
    if (articleCards = document.querySelectorAll('.article-card')) {
        var editcoverLabel  = document.querySelector('label[for="editcoverFile"]');
        var editcoverButton = document.getElementById('editcoverButton');
        var editcoverImage  = document.getElementById('editcoverImage');
        var editcoverFile   = document.getElementById('editcoverFile');
        var edittextButton = document.getElementById('edittextButton');
        var inputsPanel   = document.getElementById('edittextInputs');
        var inputTitle    = document.getElementById('arteditTitle');
        var inputBody     = document.getElementById('arteditBody');
        var inputSlug     = document.getElementById('arteditSlug');
        var $editcoverModal = $('#editcoverModal');
        var $edittextModal = $('#edittextModal');
        var editCoverCropper;
        var itemCoverImage;
        var displayTitle;
        var displayBody;
        var hiddenSlug;
        var editAction;
        articleCards.forEach((item) => {
            var action   = item.getAttribute('data-action');
            var checkbox = item.querySelector('input[name="enabled"]');
            var label    = item.querySelector('label[for="' + checkbox.id + '"]');
            checkbox.addEventListener('change', () => {
                var data = new FormData();
                data.append('form_type', 'enabled');
                data.append('enabled', checkbox.checked ? '1' : '0');
                axios.post(action, data)
                    .then((response) => {
                        if (success = response.data.success) {
                            label.innerText = success;
                        }
                    })
                    .catch((failure) => {
                        alert('Fail to chenge article status...');
                    });
            });
            var closeButton = item.querySelector('.close');
            closeButton.addEventListener('click', () => {
                if (! confirm("The article will be deleted!")) {
                    return;
                }
                var data = new FormData();
                data.append('form_type', 'delete');
                axios.post(action, data)
                    .then((response) => {
                        if (success = response.data.success) {
                            changeArtCounter('decrement');
                            item.style.display = 'none';
                        }
                    })
                    .catch((failure) => {
                        alert('Failed to delete article...');
                    });
            });
            var articleCover = item.querySelector('.article-cover');
            articleCover.addEventListener('click', () => {
                editAction = item.getAttribute('data-action');
                itemCoverImage = item.querySelector('.cover-image');
                $editcoverModal.on('show.bs.modal', () => {
                    clearModalFormMessages();
                    editcoverLabel.parentNode.style.display = '';
                    editcoverButton.setAttribute('disabled', 'disabled');
                }).on('hidden.bs.modal', () => {
                    editcoverFile.value = '';
                    editcoverImage.src  = '';
                    editCoverCropper.destroy();
                    editCoverCropper = null;
                });
                $editcoverModal.modal('show');
            });
            var articleBody = item.querySelector('.article-body');
            articleBody.addEventListener('click', () => {
                editAction   = item.getAttribute('data-action');
                displayTitle = item.querySelector('.article-title');
                displayBody  = item.querySelector('.article-body');
                hiddenSlug   = item.querySelector('.article-slug');
                $edittextModal.on('show.bs.modal', () => {
                    clearModalFormMessages();
                    inputTitle.value = displayTitle.innerText;
                    inputBody.value = displayBody.innerText;
                    inputSlug.value = hiddenSlug.innerText;
                }).on('hidden.bs.modal', () => {
                    $(this).data('bs.modal', null);
                });
                $edittextModal.modal('show');
            });
            inputTitle.addEventListener('keyup', () => {
                var slugText = getSlug(inputTitle.value);
                inputSlug.value = slugText;
            });
        });
        edittextButton.addEventListener('click', () => {
            var hideModal  = false;
            var buttonText = edittextButton.innerHTML;
            edittextButton.setAttribute('disabled', 'disabled');
            edittextButton.innerHTML = SPINNER + buttonText;
            var data = new FormData();
            data.append('form_type', 'edit-text');
            data.append('title', inputTitle.value);
            data.append('slug', inputSlug.value);
            data.append('body', inputBody.value);
            axios.post(editAction, data)
                .then((response) => {
                    if (success = response.data.success) {
                        displayTitle.innerText = success.title;
                        displayBody.innerText = success.body
                            .replace(/\s?(<br\s?\/?>)\s?/g, "\r\n");
                        hiddenSlug.innerText = success.slug;
                        hideModal = true;
                    } else {
                        modaledittextMessage('Failed to save changes...');
                    }
                })
                .catch((failure) => {
                    if (messages = failure.response.data.messages) {
                        modalEditTextMessage(messages);
                    } else {
                        modalEditTextMessage();
                    }
                })
                .then(() => {
                    edittextButton.innerHTML = buttonText;
                    edittextButton.removeAttribute("disabled");
                    if (hideModal === true) {
                        $edittextModal.modal('hide');
                    }
                });
        });
        editcoverFile.addEventListener('change', (event) => {
            var editcoverFiles = event.target.files;
            var done = (url) => {
                editcoverFile.value = '';
                editcoverImage.src = url;
                editCoverCropper = new Cropper(editcoverImage, {
                    aspectRatio: COVER_OPTIONS.aspectRatio,
                    viewMode: 3,
                    data: {
                        width:  COVER_OPTIONS.cropperWidth,
                        height: COVER_OPTIONS.cropperHeight,
                    },
                    crop: (event) => {
                        var width  = event.detail.width;
                        var height = event.detail.height;
                        if (width < COVER_OPTIONS.minWidth || height < COVER_OPTIONS.minHeight) {
                            editCoverCropper.setData({
                                width: Math.max(COVER_OPTIONS.minWidth, width),
                                height: Math.max(COVER_OPTIONS.minHeight, height),
                            });
                        }
                    },
                });
            };
            var coverfileReader;
            var editcoverFile;
            if (editcoverFiles && editcoverFiles.length > 0) {
                editcoverFile = editcoverFiles[0];
                if (URL) {
                    done(URL.createObjectURL(editcoverFile));
                } else if (FileReader) {
                    coverfileReader = new FileReader();
                    coverfileReader.onload = () => {
                        done(coverfileReader.result);
                    };
                    coverfileReader.readAsDataURL(editcoverFile);
                }
            }
            editcoverButton.removeAttribute('disabled');
            editcoverLabel.parentNode.style.display = 'none';
        });
        editcoverButton.addEventListener('click', () => {
            if (editCoverCropper) {
                var canvas = editCoverCropper.getCroppedCanvas({
                    fillColor: '#ffffff',
                    width:  COVER_OPTIONS.canvasWidth,
                    height: COVER_OPTIONS.canvasHeight
                });
                var coverBtnText = editcoverButton.innerHTML;
                editcoverButton.setAttribute('disabled', "disabled");
                editcoverButton.innerHTML = SPINNER + coverBtnText;
                canvas.toBlob((blob) => {
                    var hideModal = false;
                    var data = new FormData();
                    data.append('form_type', 'edit-cover');
                    data.append('cover', blob, 'cover.jpg');
                    data.append('original', editcoverFile.files[0]);
                    axios.post(editAction, data)
                        .then((response) => {
                            if (article = response.data.success) {
                                itemCoverImage.src = canvas.toDataURL();
                                hideModal = true;
                            } else {
                                modalEditCoverMessage('Failed to upload image...');
                            }
                        })
                        .catch((failure) => {
                            if (messages = failure.response.data.messages) {
                                modalEditCoverMessage(messages);
                            } else {
                                modalEditCoverMessage();
                            }
                        })
                        .then(() => {
                            editcoverButton.innerHTML = coverBtnText;
                            editcoverButton.removeAttribute("disabled");
                            if (hideModal === true) {
                                $editcoverModal.modal('hide');
                            }
                        });
                }, 'image/jpeg', 0.9);
            }
        });
        inputTitle.addEventListener('keyup', () => {
            var slugText = getSlug(inputTitle.value);
            inputSlug.value = slugText;
        });
    }
};

if (tomosAddArticle = document.getElementById('tomosAddArticle')) {
    var newartCover  = document.getElementById('newartCover');
    var newartFile   = document.getElementById('newartFile');
    var inputsPanel  = document.getElementById('inputsPanel');
    var inputTitle   = document.getElementById('newTitle');
    var inputSlug    = document.getElementById('newSlug');
    var newartButton = document.getElementById('newartButton');
    var newartLabel  = document.querySelector('label[for="newartFile"]');
    var newartForm   = document.getElementById('newartForm');
    var $newartModal = $('#newartModal');
    var coverCropper;
    inputTitle.addEventListener('keyup', () => {
        var slugText = getSlug(inputTitle.value);
        inputSlug.value = slugText;
    });
    newartFile.addEventListener('change', (event) => {
        var newartFiles = event.target.files;
        var done = (url) => {
            newartFile.value = '';
            newartCover.src = url;
            coverCropper = new Cropper(newartCover, {
                aspectRatio: COVER_OPTIONS.aspectRatio,
                viewMode: 3,
                data: {
                    width:  COVER_OPTIONS.cropperWidth,
                    height: COVER_OPTIONS.cropperHeight,
                },
                crop: (event) => {
                    var width  = event.detail.width;
                    var height = event.detail.height;
                    if (width < COVER_OPTIONS.minWidth || height < COVER_OPTIONS.minHeight) {
                        coverCropper.setData({
                            width: Math.max(COVER_OPTIONS.minWidth, width),
                            height: Math.max(COVER_OPTIONS.minHeight, height),
                        });
                    }
                },
            });
        };
        var coverfileReader;
        var newartFile;
        if (newartFiles && newartFiles.length > 0) {
            newartFile = newartFiles[0];
            if (URL) {
                done(URL.createObjectURL(newartFile));
            } else if (FileReader) {
                coverfileReader = new FileReader();
                coverfileReader.onload = () => {
                    done(coverfileReader.result);
                };
                coverfileReader.readAsDataURL(newartFile);
            }
        }
        newartButton.removeAttribute('disabled');
        newartLabel.parentNode.style.display = 'none';
        inputsPanel.classList.remove('d-none');
    });
    $newartModal.on('hidden.bs.modal', () => {
        clearModalFormMessages();
        newartForm.querySelector('#newBody').value = '';
        newartForm.querySelector('#newSlug').value = '';
        newartForm.querySelector('#newTitle').value = '';
        newartButton.setAttribute('disabled', "disabled");
        newartLabel.parentNode.style.display = '';
        inputsPanel.classList.add('d-none');
        newartFile.value = '';
        newartCover.src  = '';
        coverCropper.destroy();
        coverCropper = null;
    });
    newartButton.addEventListener('click', () => {
        if (coverCropper) {
            var canvas = coverCropper.getCroppedCanvas({
                fillColor: '#ffffff',
                width:  COVER_OPTIONS.canvasWidth,
                height: COVER_OPTIONS.canvasHeight
            });
            var buttonText = newartButton.innerHTML;
            newartButton.setAttribute('disabled', "disabled");
            newartButton.innerHTML = SPINNER + buttonText;
            canvas.toBlob((blob) => {
                var formData = new FormData(newartForm);
                formData.append('cover', blob, 'cover.jpg');
                formData.append('original', newartFile.files[0]);
                axios.post(newartForm.action, formData)
                    .then((response) => {
                        if (url = response.data.success.url) {
                            window.location.href = url;
                        } else {
                            modalNewArtMessage('Failed to create new article...');
                        }
                    })
                    .catch((failure) => {
                        if (messages = failure.response.data.messages) {
                            modalNewArtMessage(messages);
                        } else {
                            modalNewArtMessage();
                        }
                    })
                    .then(() => {
                        newartButton.innerHTML = buttonText;
                        newartButton.removeAttribute("disabled");
                    });
            }, 'image/jpeg', 0.9);
        }
    });
    bindArticleControlsEvent();
}

const changeArtCounter = (type) => {
    type = type || 'increment';
    var counter = document.getElementById('articlesCounter');
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

const modalEditCoverMessage = (messages) => {
    message = messages || 'Unknown Server Error';
    if (typeof message === 'object') {
        var firstKey = Object.keys(messages)[0];
        message = message[firstKey];
    }
    clearModalFormMessages();
    var alerts = document.getElementById('editcoverInputs');
    var html = '<div class="alert alert-danger alert-dismissible fade show clearfix" role="alert">' +
                   '<div class="float-left align-middle fs-1"><i class="fas fa-exclamation-triangle"></i></div>' +
                   '<div class="col-11 float-right pl-1 fs--1">' + message + '</div>' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('afterbegin', html);
};

const modalEditTextMessage = (messages) => {
    message = messages || 'Unknown Server Error';
    if (typeof message === 'object') {
        var firstKey = Object.keys(messages)[0];
        message = message[firstKey];
    }
    clearModalFormMessages();
    var alerts = document.getElementById('edittextInputs');
    var html = '<div class="alert alert-danger alert-dismissible fade show clearfix" role="alert">' +
                   '<div class="float-left align-middle fs-1"><i class="fas fa-exclamation-triangle"></i></div>' +
                   '<div class="col-11 float-right pl-1 fs--1">' + message + '</div>' +
                   '<button class="close outline-none" type="button" data-dismiss="alert" aria-label="Close">' +
                       '<span class="font-weight-light" aria-hidden="true">×</span>' +
                   '</button>' +
               '</div>';
    alerts.insertAdjacentHTML('afterbegin', html);
};

const modalNewArtMessage = (messages) => {
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
