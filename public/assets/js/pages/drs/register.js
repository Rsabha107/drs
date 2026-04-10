FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
);

const csrf = $('meta[name="csrf-token"]').attr("content");

FilePond.create(document.querySelector("#qid_files"), {
    name: "qid_files[]",
    allowMultiple: true,
    maxFiles: 2,
    maxFileSize: "2MB",
    acceptedFileTypes: [
        "image/png",
        "image/jpeg",
        "image/jpg",
        "image/gif",
        "image/webp",
        "application/pdf",
    ],
    labelIdle:
        'Drag & Drop QID Image or <span class="filepond--label-action">Browse</span>',
    server: {
        process: {
            url: "/uploads/process",
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf },
        },
        revert: {
            url: "/uploads/revert",
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": csrf },
        },
    },
});
