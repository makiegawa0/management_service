$(function () {
    $(".datepicker").datepicker({
        dateFormat: "yy/mm/dd",
    });

    // document
    //     .getElementById("submitButton")
    //     .addEventListener("click", function () {
    //         // Show a confirmation dialog
    //         let confirmDelete = confirm(
    //             "Are you sure you want to submit this form?"
    //         );

    //         if (confirmDelete) {
    //             // Perform AJAX request
    //             fetch("/your-endpoint", {
    //                 method: "POST", // Adjust the method accordingly
    //                 headers: {
    //                     "Content-Type": "application/json",
    //                     // Add any additional headers if needed
    //                 },
    //                 // Add any other fetch options if needed
    //             })
    //                 .then((response) => {
    //                     if (!response.ok) {
    //                         throw new Error("Network response was not ok");
    //                     }
    //                     return response.json();
    //                 })
    //                 .then((data) => {
    //                     // Handle the response data as needed
    //                     console.log(data);
    //                 })
    //                 .catch((error) => {
    //                     console.error(
    //                         "There was a problem with the fetch operation:",
    //                         error
    //                     );
    //                 });
    //         }
    //     });
    // Show reverse CB modal
    $("#issue-cb").on("click", function () {
        $("#setCBModal").modal("show");
    });

    // Show confirmation upon deleting admin user
    $("#delete-admin-user").on("click", function (e) {
        e.preventDefault();
        // alert($(this).attr("action"));

        let confirmDelete = confirm(
            "Are you sure you want to permanently delete this user and all associated data?"
        );

        if (confirmDelete) {
            $("#loader").css("display", "block");

            $.ajax({
                type: "DELETE",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
                },
                beforeSend: function () {},
                // success: function (response) {
                //     console.log(response);
                //     if (response.success == 1) {
                //         alert(response.message);
                //     } else {
                //         alert(response.message);
                //     }
                // },
            })
                .done(function (data, textStatus, jqXHR) {
                    alert("Succeeded.");
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Failed to delete.", errorThrown);
                });
        }
        $("#loader").css("display", "none");
    });

    // Show confirmation upon deleting user
    $("#delete-user").on("click", function (e) {
        e.preventDefault();
        // alert($(this).attr("action"));

        let confirmDelete = confirm(
            "Are you sure you want to permanently delete this user and all associated data?"
        );

        if (confirmDelete) {
            $("#loader").css("display", "block");

            $.ajax({
                type: "DELETE",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
                },
                beforeSend: function () {},
                // success: function (response) {
                //     console.log(response);
                //     if (response.success == 1) {
                //         alert(response.message);
                //     } else {
                //         alert(response.message);
                //     }
                // },
            })
                .done(function (data, textStatus, jqXHR) {
                    alert("Succeeded.");
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Failed to delete.", errorThrown);
                });
        }
        $("#loader").css("display", "none");
    });

    // When Payin ID is typed, disable amount input。
    $("#payinId").on("change", function () {
        if ($("#payinId").val()) {
            $("#amount").prop("disabled", true);
        } else {
            $("#amount").prop("disabled", false);
        }
    });

    // Send CB
    $("#send-callback").on("click", function () {
        let sUrl = $(this).data("url");
        // let sOrderId = $(this).data("order-id");
        let sPayinId = $("#payinId").val();
        let sAmount = $("#amount").val();
        let sUserId = $("#userId").val();
        // $("#setOrderMemoModal").modal("hide");
        // alert(
        //     sUrl +
        //         " payinId:" +
        //         sPayinId +
        //         "amount:" +
        //         sAmount +
        //         "userId:" +
        //         sUserId
        // );
        $("#loader").css("display", "block");

        let confirmSend = confirm("Are you sure you want to send a callback?");

        if (confirmSend) {
            $.ajax({
                url: sUrl,
                type: "POST",
                data: {
                    payin_id: sPayinId,
                    amount: sAmount,
                    user_id: sUserId,
                },
                dataType: "json",
                // context: {
                //     callback_request_log_id: sApiLogId,
                //     callback_amount: sAmount,
                //     user_id: sUserId,
                // },
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
                },
            })
                .done(function (response, textStatus, jqXHR) {
                    // console.log(response["data"]);
                    alert(
                        "The callback is sent successfully for " +
                            response["data"]["id"] +
                            "."
                    );
                    window.location.reload();

                    // if (data["header"]["status"] == "success") {
                    //     alert("Payment Received notified successfully.");
                    //     window.location.reload();
                    // } else {
                    //     alert(
                    //         $("#ajax_callback_failure_message").text() +
                    //             "(" +
                    //             data["header"]["error_code"] +
                    //             ")"
                    //     );
                    // }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Failed to send the callback.", errorThrown);
                });
        } else {
            $("#setCBModal").modal("hide");
            window.location.reload();
        }
        $("#loader").css("display", "none");
    });

    // callback_request_log_idの設定
    $(".set-payin-id-button").on("click", function () {
        let sUrl = $(this).data("url");
        let sPaymentRequestId = $(this).data("payment-request-id");
        let sPayinId = $("#payin-id-input-" + sPaymentRequestId).val();
        $("#loader").css("display", "block");
        // alert(sPayinId + sUrl);

        $.ajax({
            url: sUrl,
            type: "PUT",
            data: {
                payin_id: sPayinId,
            },
            dataType: "json",
            // context: {
            //     order_id: sOrderId,
            //     callback_request_log_id: sApiLogId,
            // },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
            },
        })
            .done(function (data, textStatus, jqXHR) {
                $(".set-payin-id-button").hide();
                $("#payin-id-input-" + sPaymentRequestId).hide();
                $(".unset-payin-id-button").show();
                $(".callback-button").show();
                // alert(
                //     "The callback is sent successfully for " +
                //         response["data"]["id"] +
                //         "."
                // );
                // window.location.reload();
                // if (data["header"]["status"] == "success") {
                //     $("#api-log-id-input-" + this.order_id).hide();
                //     $("#api-log-id-static-" + this.order_id).text(
                //         this.callback_request_log_id
                //     );
                //     $("#api-log-id-static-" + this.order_id).show();
                //     $("#set-api-log-id-button-" + this.order_id).hide();
                //     $("#unset-api-log-id-button-" + this.order_id).show();
                // } else {
                //     if (data["header"]["error_code"] == "E002") {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_not_exist_failure_message"
                //             ).text()
                //         );
                //     } else {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_failure_message"
                //             ).text()
                //         );
                //     }
                // }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                // alert($("#ajax_fail_message").text());
                alert("Failed to set payin ID.", errorThrown);
            });
        $("#loader").css("display", "none");
    });

    $(".unset-payin-id-button").on("click", function () {
        let sUrl = $(this).data("url");
        let sPaymentRequestId = $(this).data("payment-request-id");
        // let sOrderId = $(this).data("order-id");
        // alert(sUrl);
        $("#loader").css("display", "block");

        $.ajax({
            url: sUrl,
            type: "PUT",
            dataType: "json",
            // context: {
            //     order_id: sOrderId,
            // },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
            },
        })
            .done(function (data, textStatus, jqXHR) {
                $(".set-payin-id-button").show();
                $("#payin-id-input-" + sPaymentRequestId).show();
                $(".unset-payin-id-button").hide();
                $(".callback-button").hide();
                // if (data["header"]["status"] == "success") {
                //     $("#api-log-id-input-" + this.order_id).show();
                //     $("#api-log-id-input-" + this.order_id).val("");
                //     $("#api-log-id-static-" + this.order_id).hide();
                //     $("#api-log-id-static-" + this.order_id).text("");
                //     $("#set-api-log-id-button-" + this.order_id).show();
                //     $("#unset-api-log-id-button-" + this.order_id).hide();
                // } else {
                //     if (data["header"]["error_code"] == "E002") {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_not_exist_failure_message"
                //             ).text()
                //         );
                //     } else {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_failure_message"
                //             ).text()
                //         );
                //     }
                // }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("Failed to unset payin ID.", errorThrown);
                // alert($("#ajax_fail_message").text());
            });
        $("#loader").css("display", "none");
    });

    $(".callback-button").on("click", function () {
        let sUrl = $(this).data("url");
        // let sOrderId = $(this).data("order-id");

        $("#loader").css("display", "block");

        $.ajax({
            url: sUrl,
            type: "PUT",
            data: {
                issue_callback: true,
            },
            dataType: "json",
            // context: {
            //     order_id: sOrderId,
            // },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
            },
        })
            .done(function (data, textStatus, jqXHR) {
                $(".set-payin-id-button").show();
                $("#payin-id-input-" + sPaymentRequestId).show();
                $(".unset-payin-id-button").hide();
                $(".callback-button").hide();
                // if (data["header"]["status"] == "success") {
                //     $("#api-log-id-input-" + this.order_id).show();
                //     $("#api-log-id-input-" + this.order_id).val("");
                //     $("#api-log-id-static-" + this.order_id).hide();
                //     $("#api-log-id-static-" + this.order_id).text("");
                //     $("#set-api-log-id-button-" + this.order_id).show();
                //     $("#unset-api-log-id-button-" + this.order_id).hide();
                // } else {
                //     if (data["header"]["error_code"] == "E002") {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_not_exist_failure_message"
                //             ).text()
                //         );
                //     } else {
                //         alert(
                //             $(
                //                 "#ajax_set_callback_request_log_id_failure_message"
                //             ).text()
                //         );
                //     }
                // }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert("Failed to unset payin ID.", errorThrown);
                // alert($("#ajax_fail_message").text());
            });
        $("#loader").css("display", "none");
    });

    // set hurigana
    $(".user-hurigana").on("blur", function () {
        let sUrl = $(this).data("url");
        let sHurigana = $(this).val();
        $("#loader").css("display", "block");

        $.ajax({
            url: sUrl,
            type: "PUT",
            data: {
                hurigana: sHurigana,
            },
            // dataType: "json",
            context: this,
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
            },
        })
            .done(function (data, textStatus, jqXHR) {
                alert("Done setting hurigana.");
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                // alert($("#ajax_fail_message").text());
            });
        $("#loader").css("display", "none");
    });
});
