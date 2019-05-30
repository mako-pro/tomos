
const SPINNER = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';

/*--- Tomos Account Cover ---*/

if (tomosCover = document.getElementById('tomosCover')) {
    var coverImage  = document.getElementById('coverImage');
    var coverInput  = document.getElementById('coverInput');
    var coverSave   = document.getElementById('coverSave');
    var coverLabel  = document.querySelector('label[for="coverInput"]');
    var $coverModal = $('#coverModal');
    var coverCropper;
    coverInput.addEventListener('change', (e) => {
        var coverFiles = e.target.files;
        var done = (url) => {
            coverInput.value = '';
            coverImage.src   = url;
            $coverModal.modal('show');
        };
        var coverReader;
        var coverFile;
        if (coverFiles && coverFiles.length > 0) {
            coverFile = coverFiles[0];
            if (URL) {
                done(URL.createObjectURL(coverFile));
            } else if (FileReader) {
                coverReader = new FileReader();
                coverReader.onload = (e) => {
                    done(coverReader.result);
                };
                coverReader.readAsDataURL(coverFile);
            }
        }
    });
    $coverModal.on('shown.bs.modal', () => {
        coverCropper = new Cropper(coverImage, {
            aspectRatio: 13 / 3,
            viewMode: 3,
        });
    }).on('hidden.bs.modal', () => {
        coverCropper.destroy();
        coverCropper = null;
    });
    coverSave.addEventListener('click', () => {
        var canvas;
        if (coverCropper) {
            canvas = coverCropper.getCroppedCanvas({
                fillColor: '#fff',
                width:  1920,
                height: 444,
            });
            var coverSaveText = coverSave.innerHTML;
            coverSave.setAttribute('disabled', "disabled");
            coverSave.innerHTML = SPINNER + coverSaveText;
            canvas.toBlob((blob) => {
                var action = coverLabel.getAttribute('data-action');
                var formData = new FormData();
                formData.append('form_type', 'account_cover');
                formData.append('cover', blob, 'cover.png');
                axios.post(action, formData)
                    .then((response) => {
                        tomosCover.style.background = "url('" + canvas.toDataURL() + "')";
                        tomosCover.style.backgroundPosition = "center";
                        tomosCover.style.backgroundSize = "cover";
                    })
                    .catch((failure) => {
                        if (messages = failure.response.data.messages) {
                            alert(messages.cover);
                        } else {
                            alert('Failed to upload image...')
                        }
                    })
                    .then(() => {
                        coverSave.innerHTML = coverSaveText;
                        coverSave.removeAttribute("disabled");
                        $coverModal.modal('hide');
                    });
            }, 'image/jpeg', 0.8);
        }
    });
}

/*--- Tomos Account Avatar ---*/

if (tomosAvatar = document.getElementById('tomosAvatar')) {
    var avatarImage  = document.getElementById('avatarImage');
    var avatarInput  = document.getElementById('avatarInput');
    var avatarSave   = document.getElementById('avatarSave');
    var avatarLabel  = document.querySelector('label[for="avatarInput"]');
    var $avatarModal = $('#avatarModal');
    var avatarCropper;
    avatarInput.addEventListener('change', (e) => {
        var avatarFiles = e.target.files;
        var done = (url) => {
            avatarInput.value = '';
            avatarImage.src   = url;
            $avatarModal.modal('show');
        };
        var avatarReader;
        var avatarFile;
        if (avatarFiles && avatarFiles.length > 0) {
            avatarFile = avatarFiles[0];
            if (URL) {
                done(URL.createObjectURL(avatarFile));
            } else if (FileReader) {
                avatarReader = new FileReader();
                avatarReader.onload = (e) => {
                    done(avatarReader.result);
                };
                avatarReader.readAsDataURL(avatarFile);
            }
        }
    });
    $avatarModal.on('shown.bs.modal', () => {
        avatarCropper = new Cropper(avatarImage, {
            aspectRatio: 1,
            viewMode: 3,
        });
    }).on('hidden.bs.modal', () => {
        avatarCropper.destroy();
        avatarCropper = null;
    });
    avatarSave.addEventListener('click', () => {
        var canvas;
        if (avatarCropper) {
            canvas = avatarCropper.getCroppedCanvas({
                fillColor: '#fff',
                width:  200,
                height: 200,
            });
            var avatarSaveText = avatarSave.innerHTML;
            avatarSave.setAttribute('disabled', "disabled");
            avatarSave.innerHTML = SPINNER + avatarSaveText;
            canvas.toBlob((blob) => {
                var action = avatarLabel.getAttribute('data-action');
                var formData = new FormData();
                formData.append('form_type', 'account_avatar');
                formData.append('avatar', blob, 'avatar.jpg');
                axios.post(action, formData)
                    .then((response) => {
                        tomosAvatar.src = canvas.toDataURL();
                    })
                    .catch((failure) => {
                        if (messages = failure.response.data.messages) {
                            alert(messages.avatar);
                        } else {
                            alert('Failed to upload image...')
                        }
                    })
                    .then(() => {
                        avatarSave.innerHTML = avatarSaveText;
                        avatarSave.removeAttribute("disabled");
                        $avatarModal.modal('hide');
                    });
            }, 'image/jpeg', 0.8);
        }
    });
}
