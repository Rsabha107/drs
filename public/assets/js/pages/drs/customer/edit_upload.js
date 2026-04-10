$(document).ready(function () {
    console.log("edit upload js file");
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
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
        console.log("makePond called for ", inputSelector);

        const input = document.querySelector(inputSelector);
        if (!input) return null;

        let pond = null;
        let suppressDbDelete = false;
        let pendingDeleteDocIds = [];

        // ===== Save/Register button handling =====
        const $saveBtn = saveButtonSelector ? $(saveButtonSelector) : $();

        const setSaveDisabled = (disabled, text = null) => {
            if (!$saveBtn.length) return;

            // store original button HTML once
            $saveBtn.each(function () {
                const $b = $(this);
                if ($b.data("orig-html") == null) {
                    $b.data("orig-html", $b.html());
                }
            });

            $saveBtn.prop("disabled", disabled);

            if (text !== null) {
                if (disabled) {
                    $saveBtn.html(text);
                } else {
                    $saveBtn.each(function () {
                        const $b = $(this);
                        const orig = $b.data("orig-html");
                        if (orig != null) $b.html(orig);
                    });
                }
            }
        };

        const anyUploading = () =>
            pond
                ?.getFiles?.()
                .some((f) => f.status === FilePond.FileStatus.PROCESSING);

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

        console.log("Before makePond initialized for ", inputSelector);
        const init = () => {
            console.log("init called for ", inputSelector);
            if (pond) return pond;

            if (pond) {
                pond.destroy();
                pond = null;
            }

            console.log("Initializing FilePond for ", inputSelector);
            pond = FilePond.create(input, {
                name: "qid_files",
                labelIdle:
                    'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
                allowMultiple: true,
                required: true,
                maxFiles: 10,
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

            pond.setOptions({
                labelIdle:
                    'Drag & Drop Image or <span class="filepond--label-action">Browse</span>',
            });
            // ===== Disable Save/Register while uploading =====
            pond.on("processfilestart", () => {
                setSaveDisabled(true, "Uploading...");
            });

            pond.on("processfile", () => {
                if (!anyUploading())
                    setSaveDisabled(false, "Update Participant");
            });

            pond.on("processfileabort", () => {
                if (!anyUploading())
                    setSaveDisabled(false, "Update Participant");
            });

            pond.on("processfileerror", () => {
                if (!anyUploading())
                    setSaveDisabled(false, "Update Participant");
            });

            pond.on("removefile", () => {
                if (!anyUploading())
                    setSaveDisabled(false, "Update Participant");
            });

            pond.on("processfile", syncServerIds);
            pond.on("removefile", syncServerIds);
            pond.on("revertfile", syncServerIds);

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

        // const preload = (docs) => {
        //     init();
        //     clearUI();
        //     resetDeletes();

        //     (docs || []).forEach((doc) => {
        //         pond.addFile(doc.download_url, {
        //             type: "local",
        //             file: { name: doc.original_name, size: doc.size },
        //             metadata: {
        //                 docId: doc.id,
        //                 download_url: doc.download_url,
        //             },
        //         });
        //     });
        // };

        const preload = (docs) => {
            init();
            clearUI();
            resetDeletes();

            console.log("Preloading docs:", docs);

            (docs || []).forEach((doc) => {
                pond.addFile(`doc:${doc.id}`, {
                    type: "local",
                    file: { name: doc.original_name, size: doc.size },
                    metadata: { docId: doc.id, download_url: doc.download_url },
                }).then((fileItem) => {
                    fileItem.serverId = `doc:${doc.id}`; // ✅ important
                    syncServerIds();
                });
            });
        };

        return { init, refresh, clearUI, cancel, preload, resetDeletes };
    }

    // Edit pond (Update modal)
    console.log("Found input?", document.querySelector("#qid_upload_edit"));

    window.EventPondEdit = makePond({
        inputSelector: "#qid_upload_edit",
        serverIdsSelector: "#qid_server_ids_edit",
        deleteIdsSelector: "#delete_doc_ids_edit",
        saveButtonSelector: "#saveParticipantBtn", // ← class
        maxFiles: 1,
    });

    console.log("EventPondEdit:", window.EventPondEdit);
    window.EventPondEdit?.init(); // ✅ IMPORTANT

    console.log("Preloading edit documents:", window.editDocuments);

    if (window.editDocuments?.length) {
        window.EventPondEdit.preload(window.editDocuments);
    }
});
