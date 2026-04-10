$(document).ready(function () {
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    const csrf = $('meta[name="csrf-token"]').attr("content");

    function makePond({
        inputSelector,
        serverIdsSelector,
        deleteIdsSelector,
        saveButtonSelector,
        maxFiles = 10,
        blockCloseWhileUploading = true,
        showToastOnBlockedClose = true,
    }) {
        const input = document.querySelector(inputSelector);
        if (!input) return null;

        let pond = null;
        let suppressDbDelete = false;
        let pendingDeleteDocIds = [];

        // find Save button in the SAME modal
        const modalEl = input.closest(".modal");
        const saveButton = modalEl
            ? modalEl.querySelector(saveButtonSelector)
            : null;

        const lockSave = () => {
            if (!saveButton) return;
            saveButton.disabled = true;
            saveButton.classList.add("disabled");
        };

        const unlockSave = () => {
            if (!saveButton) return;
            saveButton.disabled = false;
            saveButton.classList.remove("disabled");
        };

        modalEl?.addEventListener("shown.bs.modal", unlockSave);
        modalEl?.addEventListener("hidden.bs.modal", unlockSave);

        const setDeleteIds = () =>
            $(deleteIdsSelector).val(JSON.stringify(pendingDeleteDocIds));
        const resetDeletes = () => {
            pendingDeleteDocIds = [];
            setDeleteIds();
        };

        const syncServerIds = () => {
            const ids = pond
                .getFiles()
                .filter((f) => f.serverId)
                .map((f) => f.serverId);
            $(serverIdsSelector).val(JSON.stringify(ids));
        };

        const init = () => {
            if (pond) return pond;

            pond = FilePond.create(input, {
                name: "qid_files",
                allowMultiple: true,
                maxFiles,
                allowImagePreview: true,
                imagePreviewHeight: 140,
                imagePreviewMaxHeight: 200,
                imagePreviewTransparencyIndicator: "grid",
                acceptedFileTypes: [
                    "image/jpeg",
                    "image/png",
                    "image/webp",
                    "application/pdf",
                ],
                maxFileSize: "5MB",
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

            let isUploading = false;
let originalSaveHtml = null;

            // if (modalEl && blockCloseWhileUploading) {
            //     modalEl.addEventListener("hide.bs.modal", function (e) {
            //         if (!isUploading) return;

            //         // block close
            //         e.preventDefault();
            //         e.stopPropagation();

            //         if (showToastOnBlockedClose && window.toastr) {
            //             toastr.warning("Upload still in progress");
            //         }
            //     });
            // }
            // 🔒 Disable Save when upload starts
            pond.on("addfilestart", () => {
                // isUploading = true;
                lockSave();
            });
 
            pond.on("processfilestart", () => lockSave());

            pond.on("processfile", syncServerIds);
            pond.on("removefile", syncServerIds);
            pond.on("revertfile", syncServerIds);

            // ✅ Enable Save when ALL files finished (success or error)
            pond.on("processfiles", unlockSave);
            pond.on("processfileabort", unlockSave);
            pond.on("processfileerror", unlockSave);

            // If user removes a file mid-upload
            pond.on("removefile", () => {
                const isUploading = pond
                    .getFiles()
                    .some((f) => f.status === FilePond.FileStatus.PROCESSING);

                if (!isUploading) unlockSave();
            });
            // open file (DB preload uses metadata.download_url)
            pond.on("activatefile", (file) => {
                const meta = file.getMetadata?.() || {};
                const url = meta.download_url || file.source;
                if (url) window.open(url, "_blank");
            });

            // stage delete for DB files (only on Save)
            pond.on("removefile", (error, file) => {
                if (suppressDbDelete) return;

                const meta = file?.getMetadata?.() || {};
                if (file?.origin === FilePond.FileOrigin.LOCAL && meta.docId) {
                    if (!pendingDeleteDocIds.includes(meta.docId)) {
                        pendingDeleteDocIds.push(meta.docId);
                        setDeleteIds();
                    }
                }
            });

            return pond;
        };

        const refresh = () => {
            init();
            pond?.refresh?.();
        };

        // clear UI only (no revert, no delete)
        const clearUI = () => {
            init();
            suppressDbDelete = true;
            pond.removeFiles({ revert: false });
            suppressDbDelete = false;
            $(serverIdsSelector).val("[]");
        };

        // cancel modal: revert temp uploads + reset staged deletes
        const cancel = () => {
            init();
            resetDeletes();
            suppressDbDelete = true;
            pond.removeFiles({ revert: true }); // revert temp uploads
            suppressDbDelete = false;
            $(serverIdsSelector).val("[]");
        };

        const preload = (docs) => {
            init();
            clearUI();
            resetDeletes();

            (docs || []).forEach((doc) => {
                pond.addFile(doc.download_url, {
                    type: "local",
                    file: { name: doc.original_name, size: doc.size },
                    metadata: {
                        docId: doc.id,
                        download_url: doc.download_url,
                    },
                });
            });
        };

        return { init, refresh, clearUI, cancel, preload, resetDeletes };
    }

    // Create pond (Insert modal)
    window.EventPondCreate = makePond({
        inputSelector: "#qid_upload_create",
        serverIdsSelector: "#qid_server_ids_create",
        deleteIdsSelector: "#delete_doc_ids_create",
        saveButtonSelector: ".js-save-btn", // ← class
        maxFiles: 1,
    });

    // Edit pond (Update modal)
    window.EventPondEdit = makePond({
        inputSelector: "#qid_upload_edit",
        serverIdsSelector: "#qid_server_ids_edit",
        deleteIdsSelector: "#delete_doc_ids_edit",
        saveButtonSelector: ".js-save-btn", // ← class
        maxFiles: 1,
    });

    // Modal hooks
    const createModal = document.getElementById("create_event_modal");
    if (createModal && window.EventPondCreate) {
        createModal.addEventListener("shown.bs.modal", () =>
            window.EventPondCreate.refresh()
        );
        createModal.addEventListener("hidden.bs.modal", () =>
            window.EventPondCreate.cancel()
        );
    }

    const editModal = document.getElementById("edit_event_modal");
    if (editModal && window.EventPondEdit) {
        editModal.addEventListener("shown.bs.modal", () =>
            window.EventPondEdit.refresh()
        );
        editModal.addEventListener("hidden.bs.modal", () =>
            window.EventPondEdit.cancel()
        );
    }
});
