const BASEURL = '<?= BASEURL ?>';
const tableState = {
    list_doc: { page: 1, per_page: 10, search: '', search_field: '' }
};

// ==================== GLOBAL FUNCTIONS ====================
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show" data-aos="zoom-in">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    // Auto hide after 5 seconds
    setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
}

function resetFormListDoc() {
    $('#formListDoc')[0].reset();
    $('#list_doc_id').val('');
    $('#formListDoc button[type="submit"]').text('Simpan');
    clearValidationErrors('list_doc');
}

function clearValidationErrors(prefix = '') {
    if (prefix) {
        $(`#${prefix}_rekening_id`).removeClass('is-invalid');
        $(`#${prefix}_syarat_id`).removeClass('is-invalid');
        $(`#${prefix}_rekening_id_error`).text('');
        $(`#${prefix}_syarat_id_error`).text('');
    } else {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
}

function showValidationErrors(errors, prefix = '') {
    for (const field in errors) {
        const fieldId = prefix ? `${prefix}_${field}` : field;
        $(`#${fieldId}`).addClass('is-invalid');
        $(`#${fieldId}_error`).text(errors[field]);
    }
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css({ 'overflow': '', 'padding-right': '' });
}

// Load table data dengan pagination dan search
function loadTableData(tipe) {
    const state = tableState[tipe];
    const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1).replace('_', '')}`;

    $(`#${tableId} tbody`).html(`
        <tr>
            <td colspan="4" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </td>
        </tr>
    `);

    $.ajax({
        url: BASEURL + "admin/list_doc",
        method: 'POST',
        data: {
            a: "data",
            page: state.page,
            per_page: state.per_page,
            search: state.search,
            search_field: state.search_field
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                let tbody = $(`#${tableId} tbody`).empty();

                if (res.data.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <br>
                                    ${state.search ? 'Tidak ada data yang sesuai dengan pencarian' : 'Belum ada data relasi syarat & rekening'}
                                </div>
                            </td>
                        </tr>
                    `);
                } else {
                    res.data.forEach(row => {
                        let tr = $("<tr>");

                        // Kode Rekening dengan badge
                        tr.append($("<td>").html(`
                            <code class="text-primary">${row.kode_rekening || '-'}</code>
                        `));

                        // Nama Belanja
                        tr.append($("<td>").text(row.nama_belanja || '-'));

                        // Syarat Verifikasi dengan urutan
                        const syaratText = row.urutan ?
                            `<strong>${row.urutan}.</strong> ${row.nama_syarat || '-'}` :
                            (row.nama_syarat || '-');
                        tr.append($("<td>").html(syaratText));

                        let actions = `
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-edit" data-id="${row.list_doc_id}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                                <button class="btn btn-outline-danger btn-delete" data-id="${row.list_doc_id}" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        `;
                        tr.append($("<td>").html(actions));
                        tbody.append(tr);
                    });
                }

                // Update pagination controls
                updatePaginationControls(tipe, res.pagination);
            } else {
                showAlert("Gagal memuat data: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Server error saat mengambil data.", 'danger');
        }
    });
}

// Update pagination controls
function updatePaginationControls(tipe, pagination) {
    const { current_page, per_page, total_rows, total_pages } = pagination;

    // Update pagination info
    const start = ((current_page - 1) * per_page) + 1;
    const end = Math.min(current_page * per_page, total_rows);
    $(`.pagination-info[data-tipe="${tipe}"]`).html(`
        Menampilkan ${start} - ${end} dari ${total_rows} data
    `);

    // Update pagination buttons
    const paginationEl = $(`.pagination-controls[data-tipe="${tipe}"]`).empty();

    // Previous button
    paginationEl.append(`
        <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page - 1}">‹</a>
        </li>
    `);

    // Page buttons
    const maxVisiblePages = 5;
    let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(total_pages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        paginationEl.append(`
            <li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }

    // Next button
    paginationEl.append(`
        <li class="page-item ${current_page === total_pages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page + 1}">›</a>
        </li>
    `);
}

// Event handlers untuk pagination
$(document).on('click', '.pagination-controls .page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    const tipe = $(this).closest('.pagination-controls').data('tipe');

    if (page) {
        tableState[tipe].page = page;
        loadTableData(tipe);
    }
});

// Event handlers untuk search
$(document).on('click', '.search-btn', function() {
    const tipe = $(this).data('tipe');
    const searchInput = $(`.search-input[data-tipe="${tipe}"]`);
    const searchField = $(`.search-field-select[data-tipe="${tipe}"]`);

    tableState[tipe].search = searchInput.val();
    tableState[tipe].search_field = searchField.val();
    tableState[tipe].page = 1;

    loadTableData(tipe);
});

$(document).on('keypress', '.search-input', function(e) {
    if (e.which === 13) {
        const tipe = $(this).data('tipe');
        $(`.search-btn[data-tipe="${tipe}"]`).click();
    }
});

// Event handlers untuk per page change
$(document).on('change', '.per-page-select', function() {
    const tipe = $(this).data('tipe');
    tableState[tipe].per_page = $(this).val();
    tableState[tipe].page = 1;
    loadTableData(tipe);
});

$(document).ready(function() {
    // Load initial data
    loadTableData('list_doc');

    // Form submission handler
    $("#formListDoc").submit(function(e) {
        e.preventDefault();
        submitForm('list_doc');
    });

    // Edit button handler
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");
        editData(id);
    });

    // Delete button handler
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        $("#deleteId").val(id);

        // Reset delete details
        $("#deleteDetails").empty();

        $("#deleteModal").modal("show");
        cleanupStuckModals();
    });

    // Confirm delete
    $("#confirmDelete").click(function() {
        const deleteBtn = $(this);
        const id = $("#deleteId").val();
        const csrfToken = $("#deleteCsrf").val();

        deleteBtn.prop('disabled', true);
        deleteBtn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: BASEURL + "admin/list_doc",
            method: 'POST',
            data: {
                a: "hapus",
                id: id,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function(res) {
                deleteBtn.prop('disabled', false);
                deleteBtn.find('.spinner-border').addClass('d-none');

                if (res.status === "success") {
                    $("#deleteModal").modal("hide");
                    showAlert(res.message, 'success');
                    loadTableData('list_doc');
                } else {
                    showAlert("Gagal hapus: " + res.message, 'danger');
                }
            },
            error: function(xhr, status, error) {
                deleteBtn.prop('disabled', false);
                deleteBtn.find('.spinner-border').addClass('d-none');
                showAlert("Server error saat menghapus.", 'danger');
            }
        });
    });
});

function submitForm(tipe) {
    clearValidationErrors(tipe);

    const form = $(`#form${tipe.charAt(0).toUpperCase() + tipe.slice(1).replace('_', '')}`);
    const formData = form.serializeArray();
    const data = {};
    formData.forEach(f => data[f.name] = f.value);

    // Show loading state
    const submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/list_doc",
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                // Success case - reset form and reload data
                form[0].reset();
                $(`#${tipe}_id`).val('');
                submitBtn.text('Simpan');
                showAlert(res.message, 'success');
                loadTableData(tipe);
                $('html, body').animate({ scrollTop: 0 }, 500);
            } else {
                if (res.errors) {
                    showValidationErrors(res.errors, tipe);
                } else {
                    showAlert("Gagal simpan: " + res.message, 'danger');
                }
            }
        },
        error: function(xhr, status, error) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menyimpan.", 'danger');
        }
    });
}

function editData(id) {
    clearValidationErrors('list_doc');

    $.ajax({
        url: BASEURL + "admin/list_doc",
        method: 'POST',
        data: {
            a: "get",
            id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                const item = res.data;
                const form = $('#formListDoc');

                // Fill form with data
                $('#list_doc_id').val(item.list_doc_id);
                $('#list_doc_rekening_id').val(item.rekening_id);
                $('#list_doc_syarat_id').val(item.syarat_id);

                form.find('button[type="submit"]').text('Update');

                // Scroll to form
                $('html, body').animate({
                    scrollTop: form.offset().top - 100
                }, 500);
            } else {
                showAlert("Gagal mengambil data: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Server error saat mengambil data.", 'danger');
        }
    });
}