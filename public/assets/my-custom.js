function loadingStart(title = null) {
    // $(".loader-wrapper").fadeOut("slow", function () {
    //     $(this).css("display", "block");
    // });
    Swal.fire({
        title: "Loading...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            $(".swal2-confirm").removeClass("btn");
            $(".swal2-deny").removeClass("btn");
            $(".swal2-cancel").removeClass("btn");
        },
    });

    // return new swal("Loading", "!", "success");
}

function loadingStop() {
    Swal.close();
    // $(".loader-wrapper").fadeOut("slow", function () {
    //     $(this).css("display", "none");
    // });
}
function showSuccess(title, type = null) {
    Swal.fire({
        position: "center",
        icon: type ? type : "success",
        title: title,
        showConfirmButton: !1,
        timer: 1500,
        showCloseButton: !0,
    });
}

function showWarn(title) {
    toastr.error(title);
}
function deleteRecordAjax(url) {
    return new swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        showCancelButton: !0,
        customClass: {
            confirmButton: "btn btn-primary w-xs me-2 mb-1",
            cancelButton: "btn btn-danger w-xs mb-1",
        },
        confirmButtonText: "Yes, Delete It!",
        buttonsStyling: !1,
        showCloseButton: !0,
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (data) {
                    if (data.success == true) {
                        showSuccess(data.message, "success");
                        if (typeof myTable !== "undefined" && myTable) {
                            myTable.ajax.reload();
                        } else {
                            location.reload();
                        }
                    } else {
                        showSuccess(data.message, "error");
                        location.reload();
                    }
                },
                error: function (error) {
                    let message = "Network error";
                    if (error.responseJSON) {
                        message = error.responseJSON.message;
                    }
                    showWarn(message);
                },
            });
        }
    });
}

function addFormData(
    e,
    method,
    url,
    redirectUrl,
    data,
    select_id = null,
    model = null
) {
    loadingStart();
    // for (instance in CKEDITOR.instances) {
    //     CKEDITOR.instances[instance].updateElement();
    // }
    let from = document.getElementById(data);
    let record = new FormData(from);
    // if ($('.note-editable').html() != '') {
    //     record.append('descriptions', $('.note-editable').html())
    // }
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        type: method,
        url: url,
        data: record,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            loadingStop();
            $(":input", data).not(":button, :submit, :reset, :hidden").val("");
            if ($(".upload-zone")[0] != undefined) {
                $(".upload-zone")[0].dropzone.removeAllFiles(true);
            }
            if (response.status != false) {
                $("#exportModal").modal("hide");
                showSuccess(response.message, "success");
                if (select_id) {
                    getSelectRecord(
                        baseUrl +
                            "/select-record?model=" +
                            encodeURIComponent(model),
                        select_id
                    );

                    $("#addModel").modal("hide");
                } else {
                    if (typeof myTable !== "undefined" && myTable) {
                        $("#addModel").modal("hide");
                        $("#editModel").modal("hide");
                        myTable.ajax.reload();
                    } else {
                        if (response.data) {
                            // window.open(response.data, "_blank");
                            if (response.data.redirect_url) {
                                window.location = response.data.redirect_url;
                            } else {
                                setTimeout(function () {
                                    window.location = redirectUrl;
                                }, 2000);
                            }
                            // return;
                        } else {
                            setTimeout(function () {
                                window.location = redirectUrl;
                            }, 2000);
                        }
                    }
                }
            } else {
                showWarn(response.message, "error");
            }
        },
        error: function (xhr) {
            loadingStop();
            let data = "";
            if (xhr.status == 400 || xhr.status == 422) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    data += "</br>" + value;
                });
                showWarn(data);
            }
            if (xhr.status == 500) {
                showWarn(xhr.responseJSON.message);
            }
        },
    });
}

function getSalary(e, url) {
    let employee_id = e.target.value;
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
        },
        success: function (response) {
            $("#salary").val("");
            $("#salary").val(response);
        },
    });
}

function addUserCheck(e, url, type) {
    loadingStart();
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "post",
        data: {
            type: type,
        },
        success: function (response) {
            loadingStop();
            if (response.status != false) {
                showSuccess(response.message, "success");
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else {
                showWarn(response.message, "error");
            }
        },
        error: function (xhr) {
            loadingStop();
            $(e.target).prop("disabled", false);
            let data = "";
            if (xhr.status == 400 || xhr.status == 422) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    data += "</br>" + value;
                });
                showWarn(data);
            }
            if (xhr.status == 500) {
                showWarn(xhr.responseJSON.message);
            }
        },
    });
}

function getEditRecord(url, modelId) {
    loadingStart();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "get",
        success: function (response) {
            loadingStop();
            $("#editRecord").html("");
            $("#editRecord").html(response);
            $(modelId).modal("show");
        },
    });
}
function getAddRecord(url, modelId) {
    loadingStart();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "get",
        success: function (response) {
            loadingStop();
            $("#addRecord").html("");
            $("#addRecord").html(response);
            $(modelId).modal("show");
        },
    });
}
function getSelectRecord(url, select_id) {
    loadingStart();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "get",
        success: function (response) {
            loadingStop();
            $("#" + select_id).html("");
            $("#" + select_id).html(response);
        },
    });
}
