$(document).ready(function () {
    function calculatePercentage(
        orderedSelector,
        redeemedSelector,
        outputSelector,
    ) {
        const ordered = parseFloat($(orderedSelector).val());
        const redeemed = parseFloat($(redeemedSelector).val());

        // stop if either is null / empty / zero
        if (!ordered || !redeemed) {
            $(outputSelector).val("");
            return;
        }

        const pct = (redeemed / ordered) * 100;
        $(outputSelector).val(pct.toFixed(2));
    }

    // 🔹 Attendance
    $("#demand_of_day, #attended").on("input change keyup", function () {
        calculatePercentage(
            "#demand_of_day",
            "#attended",
            "#attendance_percentage",
        );
    });

    // 🔹 Volunteer meal redemption
    $("#checked_in_volunteers, #meals_redeemed_volunteers").on(
        "input change keyup",
        function () {
            calculatePercentage(
                "#checked_in_volunteers",
                "#meals_redeemed_volunteers",
                "#volunteer_meal_percentage",
            );
        },
    );

    // 🔹 Staff meal redemption
    $("#checked_in_staff, #meals_redeemed_staff").on(
        "input change keyup",
        function () {
            calculatePercentage(
                "#checked_in_staff",
                "#meals_redeemed_staff",
                "#staff_meal_percentage",
            );
        },
    );

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
            btns: [
                ["strong", "em", "underline"],
                ["unorderedList", "orderedList"],
                ["link"],
                ["removeformat"],
                ["undo", "redo"],
                ["fontfamily", "fontsize"],
                ["indent", "outdent"],
                ["foreColor", "backColor"],
                ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            ],
            autogrow: true,
            resetCss: true,
        });
    });

    $(function () {
        $("#general_issues").trumbowyg({
            btns: [
                ["strong", "em", "underline"],
                ["unorderedList", "orderedList"],
                ["link"],
                ["removeformat"],
                ["undo", "redo"],
                ["fontfamily", "fontsize"],
                ["indent", "outdent"],
                ["foreColor", "backColor"],
                ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            ],
            autogrow: true,
            resetCss: true,
        });
    });

    $(function () {
        $("#fa_observations").trumbowyg({
            btns: [
                ["strong", "em", "underline"],
                ["unorderedList", "orderedList"],
                ["link"],
                ["removeformat"],
                ["undo", "redo"],
                ["fontfamily", "fontsize"],
                ["indent", "outdent"],
                ["foreColor", "backColor"],
                ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
            ],
            autogrow: true,
            resetCss: true,
        });
    });

    document
        .getElementById("btnVocImport")
        .addEventListener("click", async () => {
            const fileInput = document.getElementById("vocExcel");
            const file = fileInput.files[0];
            if (!file) {
                alert("Please choose an Excel file.");
                return;
            }

            const url =
                document.getElementById("btnVocImport").dataset.importUrl;

            const msg = document.getElementById("vocImportMsg");
            msg.textContent = "Importing...";

            const fd = new FormData();
            fd.append("excel", file);
            fd.append(
                "draft_token",
                document.getElementById("draft_token").value,
            );

            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: fd,
            });

            if (!res.ok) {
                msg.textContent = "Import failed.";
                return;
            }

            const data = await res.json();
            if (data.ok) {
                document.getElementById("vocTableContainer").innerHTML =
                    data.html;
                msg.textContent = "Imported successfully.";
            } else {
                msg.textContent = "Import failed.";
            }

            
        });
});
