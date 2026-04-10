("use strict");
function queryParams(p) {
    return {
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}

window.icons = {
    refresh: "bx-refresh",
    toggleOn: "bx-toggle-right",
    toggleOff: "bx-toggle-left",
    fullscreen: "bx-fullscreen",
    columns: "bx-list-ul",
    export_data: "bx-list-ul",
};

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>';
}

function clientFormatter(value, row, index) {
    html =
        '<div class="d-flex align-items-center mb-3 mb-md-0 mb-xl-3">' +
        '<div class="avatar avatar-xl me-3"><img class="rounded-circle" src="../../../assets/img/team/72x72/58.webp" alt="" /></div>' +
        "<div>" +
        "<h5>" +
        row.first_name +
        " " +
        row.last_name +
        "</h5>" +
        '<div class="dropdown"><a class="text-body-secondary dropdown-toggle text-decoration-none dropdown-caret-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">' +
        row.email +
        "</a>" +
        "</div>" +
        "</div>" +
        "</div>";

    return html;
}

function projectsAssignedFormatter(value, row, index) {
    html =
        '<button type="button" class="btn btn-info p-2 rounded-circle btn-lg">' +
        row.projects +
        '<i class="fa-solid fa-circle"></i></button>';

    // html = '<button class="btn btn-primary d-flex align-items-center p-2 rounded-circle"  type="button"><span class="badge bg-primary-dark ms-2">'+row.projects+'</span></button>'
    return html;
}

function tasksAssignedFormatter(value, row, index) {
    html =
        '<button type="button" class="btn btn-info p-2 rounded-circle btn-lg">' +
        row.tasks +
        "</button>";
    // html = '<button class="btn btn-primary d-flex align-items-center p-2 rounded-circle"  type="button"><span class="badge bg-primary-dark ms-2">'+row.projects+'</span></button>'
    return html;
}

function assignedFormatter(value, row, index) {
    html =
        '<button class="btn btn-primary d-flex align-items-center rounded-circle"  type="button">Projects<span class="badge bg-primary-dark ms-2">' +
        row.projects +
        "</span></button>";
    html +=
        '<button class="btn btn-primary d-flex align-items-center" type="button">Projects<span class="badge bg-primary-dark ms-2">' +
        row.projects +
        "</span></button>";

    return html;
    return (
        '<div class="d-flex justify-content-start align-items-center"><div class="text-center mx-4"><span class="badge rounded-pill bg-primary" >' +
        row.projects +
        "</span><div>" +
        label_projects +
        "</div></div>" +
        '<div class="text-center"><span class="badge rounded-pill bg-primary" >' +
        row.tasks +
        "</span><div>" +
        label_tasks +
        "</div></div></div>"
    );
}

function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-clients" id="editClients" data-id="' +
            row.id +
            '" title="' +
            label_update +
            '" data-table="tags_table" class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
            "<button title=" +
            label_delete +
            ' type="button" data-table="clients_table" class="btn delete" id="deleteClients" data-id=' +
            row.id +
            ' data-type="clients">' +
            '<i class="bx bx-trash text-danger mx-1"></i>' +
            "</button>",
    ];
}
