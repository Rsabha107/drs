$(document).ready(function () {
    console.log("FilePond:", typeof FilePond);
    console.log("ImagePreview plugin:", typeof FilePondPluginImagePreview);

    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    const input = document.querySelector("#qid_upload");
    const csrf = $('meta[name="csrf-token"]').attr("content");

    let pond = null;
    let suppressDbDelete = false;

    function resetPendingDeletes() {
        pendingDeleteDocIds = [];
        $("#delete_doc_ids").val(JSON.stringify(pendingDeleteDocIds));
    }

    function initPondIfNeeded() {
        if (pond) return;

        pond = FilePond.create(input, {
            name: "qid_files",
            allowMultiple: true,
            maxFiles: 1,
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

        // keep hidden input synced
        const syncServerIds = () => {
            const ids = pond
                .getFiles()
                .filter((f) => f.serverId)
                .map((f) => f.serverId);
            $("#qid_server_ids").val(JSON.stringify(ids));
        };

        pond.on("processfile", syncServerIds);
        pond.on("removefile", syncServerIds);
        pond.on("revertfile", syncServerIds);

        // pond.on("activatefile", function (file) {
        //     const meta = file.getMetadata?.() || {};
        //     const url =
        //         file.serverId || // freshly uploaded temp
        //         meta.download_url || // DB file
        //         file.source; // local preload

        //     if (!url) return;

        //     window.open(url, "_blank");
        // });

        pond.on("activatefile", function (file) {
            const meta = file.getMetadata?.() || {};
            const url = meta.download_url || file.source;
            if (url) window.open(url, "_blank");
        });

        pond.on("removefile", function (error, file) {
            if (suppressDbDelete) return;

            const meta = file?.getMetadata?.() || {};

            // ✅ If it's a preloaded DB file, mark it for deletion (DON'T call AJAX now)
            if (file?.origin === FilePond.FileOrigin.LOCAL && meta.docId) {
                if (!pendingDeleteDocIds.includes(meta.docId)) {
                    console.log("Marking docId for deletion:", meta.docId);
                    pendingDeleteDocIds.push(meta.docId);
                    // 🔥 THIS updates the hidden input
                    $("#delete_doc_ids").val(
                        JSON.stringify(pendingDeleteDocIds)
                    );
                }
                return;
            }

            // ✅ If it's a newly uploaded temp file, FilePond will call server.revert automatically
            // and your qid_server_ids sync will remove its serverId.
        });

        // delete preloaded DB doc when removed
        // pond.on("removefile", function (error, file) {
        //     if (suppressDbDelete) return;

        //     // ✅ only delete DB files that were preloaded (type: "local")
        //     if (file?.origin !== FilePond.FileOrigin.LOCAL) return;

        //     const meta = file?.getMetadata?.() || {};
        //     if (!meta.deleteUrl) return;

        //     // optional confirm
        //     // if (!confirm("Delete this attachment?")) return;

        //     $.ajax({
        //         url: meta.deleteUrl,
        //         type: "DELETE",
        //         headers: { "X-CSRF-TOKEN": csrf },
        //     });
        // });
    }

    function clearPond() {
        initPondIfNeeded();
        suppressDbDelete = true;
        pond.removeFiles({ revert: false });

        setTimeout(() => {
            suppressDbDelete = false;
        }, 300);
        $("#qid_server_ids").val("[]");
    }

    function preloadDocs(docs) {
        initPondIfNeeded();

        // avoid duplicates: clear then preload
        suppressDbDelete = true;

        setTimeout(() => {
            suppressDbDelete = false;

            (docs || []).forEach((doc) => {
                pond.addFile(doc.download_url, {
                    type: "local",
                    file: { name: doc.original_name, size: doc.size },
                    metadata: {
                        docId: doc.id,
                        deleteUrl: doc.delete_url,
                    },
                });
            });
        }, 300);
    }

    // Expose helpers to other files
    window.EventPond = {
        init: initPondIfNeeded,
        clear: clearPond,
        preload: preloadDocs,
        resetDeletes: resetPendingDeletes,
    };

    // Optional modal hooks (recommended)
    const modalEl = document.getElementById("edit_event_modal");
    if (modalEl) {
        modalEl.addEventListener("shown.bs.modal", function () {
            initPondIfNeeded();
            setTimeout(() => {
                if (pond?.refresh) pond.refresh();
            }, 200);
        });
        // modalEl.addEventListener("hidden.bs.modal", function () {
        //     clearPond();
        // });
        modalEl.addEventListener("hidden.bs.modal", function () {
            // cancel staged deletions
            initPondIfNeeded();
            resetPendingDeletes();

            // remove temp uploads (revert them on server)
            suppressDbDelete = true;
            pond.removeFiles({ revert: true }); // ✅ will call /uploads/revert for temp files
            suppressDbDelete = false;

            $("#qid_server_ids").val("[]");
        });
    }
});
