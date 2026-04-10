$(document).ready(function () {
    console.log("Customer Create JS loaded");

    const form = document.getElementById("spinner-form");
    const submitAction = document.getElementById("submit_action");

    console.log("Form:", form);
    console.log("Submit Action:", submitAction);

    document.getElementById("saveDraftBtn")?.addEventListener("click", () => {
        submitAction.value = "draft";
        form.submit();
    });
    document
        .getElementById("publishReportBtn")
        ?.addEventListener("click", () => {
            submitAction.value = "submitted";
            form.submit();
        });

    $(function () {
        $("#actions_vum").trumbowyg({
            // btns: [
            //     ["strong", "em", "underline"],
            //     ["unorderedList", "orderedList"],
            //     ["link"],
            //     ["removeformat"],
            //     ["undo", "redo"],
            //     ["fontfamily", "fontsize"],
            //     ["indent", "outdent"],
            //     ["foreColor", "backColor"],
            //     ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            //     ['resize'],
            // ],
            autogrow: true,
            resetCss: true,
        });
    });

    $(function () {
        $("#mobility_section").trumbowyg({
            // btns: [
            //     ["strong", "em", "underline"],
            //     ["unorderedList", "orderedList"],
            //     ["link"],
            //     ["removeformat"],
            //     ["undo", "redo"],
            //     ["fontfamily", "fontsize"],
            //     ["indent", "outdent"],
            //     ["foreColor", "backColor"],
            //     ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            // ],
            autogrow: true,
            resetCss: true,
        });
    });

    $(function () {
        $("#general_issues").trumbowyg({
            // btns: [
            //     ["strong", "em", "underline"],
            //     ["unorderedList", "orderedList"],
            //     ["link"],
            //     ["removeformat"],
            //     ["undo", "redo"],
            //     ["fontfamily", "fontsize"],
            //     ["indent", "outdent"],
            //     ["foreColor", "backColor"],
            //     ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            // ],
            autogrow: true,
            resetCss: true,
        });
    });

    $(function () {
        $("#fa_observations").trumbowyg({
            // btns: [
            //     ["strong", "em", "underline"],
            //     ["unorderedList", "orderedList"],
            //     ["link"],
            //     ["removeformat"],
            //     ["undo", "redo"],
            //     ["fontfamily", "fontsize"],
            //     ["indent", "outdent"],
            //     ["foreColor", "backColor"],
            //     ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            // ],
            autogrow: true,
            resetCss: true,
        });
    });

    document
        .getElementById("btnVocImport")
        .addEventListener("click", async () => {
            const fileInput = document.getElementById("vocExcel");
            const file = fileInput.files[0];
            const msg = document.getElementById("vocImportMsg");
            const clearBtn = document.getElementById("btnVocClear");

            clearBtn.style.display = "block";

            if (!file) {
                alert("Please select an Excel file");
                return;
            }

            msg.textContent = "Importing...";

            // 1️⃣ IMPORT EXCEL
            const fd = new FormData();
            fd.append("excel", file);
            fd.append(
                "draft_token",
                document.getElementById("draft_token").value,
            );

            const importRes = await fetch(window.APP.routes.vocImport, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: fd,
            });

            if (!importRes.ok) {
                msg.textContent = "Import failed.";
                return;
            }

            // 2️⃣ LOAD PREVIEW HTML (THIS IS WHERE IT GOES)
            const draftToken = document.getElementById("draft_token").value;

            const previewRes = await fetch(
                window.APP.routes.vocPreview +
                    "?draft_token=" +
                    encodeURIComponent(draftToken),
                { headers: { "X-Requested-With": "XMLHttpRequest" } },
            );

            if (!previewRes.ok) {
                msg.textContent = "Imported, but preview failed.";
                return;
            }

            // 3️⃣ INJECT INTO CONTAINER
            const html = await previewRes.text();
            document.getElementById("vocTableContainer").innerHTML = html;

            msg.textContent = "VOC issues imported and previewed successfully.";
        });

    const clearBtn = document.getElementById("btnVocClear");
    const msg = document.getElementById("vocImportMsg");

    clearBtn.addEventListener("click", async () => {
        if (!confirm("Remove all imported VOC issues for this report draft?"))
            return;

        msg.textContent = "Removing imported issues...";

        const draftToken = document.getElementById("draft_token").value;

        const res = await fetch(window.APP.routes.vocClear, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ draft_token: draftToken }),
        });

        if (!res.ok) {
            msg.textContent = "Failed to remove issues.";
            return;
        }

        // Clear UI
        document.getElementById("vocTableContainer").innerHTML =
            `<div class="text-muted p-3">No VOC issues imported yet.</div>`;

        // Reset file input too (optional)
        const fileInput = document.getElementById("vocExcel");
        if (fileInput) fileInput.value = "";

        clearBtn.style.display = "none";
        msg.textContent = "Imported VOC issues removed.";
    });

    $("#match_number").on("change", function () {
        console.log("Match number changed:", $(this).val());
        const matchId = $(this).val();
        const fp = $("#edit_match_date")[0]._flatpickr;
        if (!matchId) {
            $("#edit_match_date").val("");
            $("#stage").val("");
            $("#team_a_name").val("");
            $("#team_b_name").val("");
            return;
        }

        $.ajax({
            url: "/ajax/match-details/" + matchId,
            type: "GET",
            dataType: "json",
            success: function (match) {
                console.log("Match details:", match);

                if (fp) {
                    fp.setDate(match.match_date, true);
                }
                $("#stage").val(match.stage ?? "");
                $("#team_a_name")
                    .val(match.pma1 ?? "")
                    .trigger("change");
                $("#team_b_name")
                    .val(match.pma2 ?? "")
                    .trigger("change");
            },
            error: function () {
                $("#edit_match_date").val("");
                $("#stage").val("");
                $("#team_a_name").val("");
                $("#team_b_name").val("");
            },
        });
    });

    let matchChoices = null;

    function initMatchChoices() {
        console.log("Initializing match number Choices.js");
        const el = document.getElementById("match_number");
        if (!el) return;

        matchChoices = new Choices(el, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: "",
            removeItemButton: true,
            placeholder: true,
            placeholderValue: "Select Match Number",
        });
    }

    function disableMatchSelect(text = "Select Match Number") {
        $("#match_number").prop("disabled", true);

        if (matchChoices) {
            matchChoices.disable();
            matchChoices.removeActiveItems();
            matchChoices.clearChoices();
            matchChoices.setChoices(
                [
                    {
                        value: "",
                        label: text,
                        selected: true,
                        disabled: true,
                    },
                ],
                "value",
                "label",
                true,
            );
        }
    }

    function enableMatchSelect() {
        $("#match_number").prop("disabled", false);
        if (matchChoices) {
            matchChoices.enable();
        }
    }

    function loadMatchesByVenue(venueId, selectedMatchId = null) {
        console.log("Loading matches for venue:", venueId, "Selected match ID:", selectedMatchId);
        if (!venueId) {
            disableMatchSelect();
            return;
        }

        enableMatchSelect();

        $.ajax({
            url: "/ajax/matches-by-venue/" + venueId,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                disableMatchSelect("Loading...");
            },
            success: function (matches) {
                enableMatchSelect();

                const data = [
                    {
                        value: "",
                        label: "Please choose a value",
                        selected: !selectedMatchId, // selected only if no existing value
                        disabled: true,
                    },
                    ...matches.map((match) => ({
                        value: String(match.id),
                        label: match.match_number,
                        selected:
                            selectedMatchId &&
                            String(match.id) === String(selectedMatchId),
                    })),
                ];

                matchChoices.removeActiveItems();
                matchChoices.clearChoices();
                matchChoices.setChoices(data, "value", "label", true);

                if (selectedMatchId) {
                    matchChoices.setChoiceByValue(String(selectedMatchId));
                }
            },
            error: function () {
                disableMatchSelect("Unable to load matches");
            },
        });
    }

    // $(document).ready(function () {
        initMatchChoices();

        const venueId = $("#venue_id").val();
        const selectedMatchId = $("#selected_match_number").val();

        if (venueId) {
            loadMatchesByVenue(venueId, selectedMatchId);
        } else {
            disableMatchSelect();
        }

        $("#venue_id").on("change", function () {
            loadMatchesByVenue($(this).val(), null);
        });
    // });
});
