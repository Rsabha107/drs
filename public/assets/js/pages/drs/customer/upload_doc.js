$(document).ready(function () {
    console.log("Upload Doc JS loaded");
    FilePond.registerPlugin(
        // FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
    );

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const input = document.querySelector("#qid_files");
    const publishReportBtn = document.getElementById("publishReportBtn");
    const saveDraftBtn = document.getElementById("saveDraftBtn");
    const serverIdsInput = document.getElementById("qid_server_ids");

    const actionButtons = [publishReportBtn, saveDraftBtn].filter(Boolean);

    if (!input) return;

    const syncServerIds = () => {
        const ids = pond
            .getFiles()
            .filter((f) => f.serverId)
            .map((f) => f.serverId);

        serverIdsInput.value = JSON.stringify(ids);
    };

    const setButtonsDisabled = (disabled) => {
        actionButtons.forEach((btn) => {
            btn.disabled = disabled;
            btn.classList.toggle("is-disabled", disabled);

            btn.dataset.originalText ??= btn.innerText;
            btn.innerText = disabled ? "Uploading…" : btn.dataset.originalText;
        });
    };

    const pond = FilePond.create(document.querySelector("#qid_files"), {
        name: "qid_files[]",
        labelIdle:
            'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
        allowMultiple: true,
        maxFiles: 10,
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

    pond.on("processfile", syncServerIds);
    pond.on("removefile", syncServerIds);

    pond.setOptions({
        labelIdle:
            'Drag & Drop Image or <span class="filepond--label-action">Browse</span>',
    });

    const anyUploading = () =>
        pond
            .getFiles()
            .some((f) => f.status === FilePond.FileStatus.PROCESSING);

    // Disable immediately when upload starts
    pond.on("processfilestart", () => setButtonsDisabled(true));

    // Re-enable when upload completes and nothing else uploading
    pond.on("processfile", () => {
        if (!anyUploading()) setButtonsDisabled(false);
    });

    // If upload is aborted or errors out
    pond.on("processfileabort", () => setButtonsDisabled(false));
    pond.on("processfileerror", () => setButtonsDisabled(false));

    // Removing file should re-enable (unless another upload still running)
    pond.on("removefile", () => {
        if (!anyUploading()) setButtonsDisabled(false);
    });
});
