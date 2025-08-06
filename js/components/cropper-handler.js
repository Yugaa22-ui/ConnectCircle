let cropper;
const fileInput = document.getElementById('profileInput');
const image = document.getElementById('cropperImage');
const preview = document.getElementById('preview');
const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));

fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        image.src = event.target.result;
        image.onload = function () {
            cropperModal.show();
        };
    };
    reader.readAsDataURL(file);
});

document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {
    if (cropper) cropper.destroy();
    cropper = new Cropper(image, {
        aspectRatio: 1,
        initialAspectRatio: 1,
        viewMode: 2,
        autoCropArea: 1,
        dragMode: 'move',
        movable: true,
        zoomable: true,
        rotatable: false,
        scalable: false,
        responsive: true
    });
});

document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

document.getElementById('cropBtn').addEventListener('click', function () {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300
    });

    canvas.toBlob(function (blob) {
        const file = new File([blob], "cropped_profile.jpg", { type: "image/jpeg" });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        preview.src = URL.createObjectURL(blob);
        preview.classList.remove('d-none');

        cropperModal.hide();
    }, 'image/jpeg');
});
