(function ($) {
  $(function () {
    $("#list-grants-tb").dataTable();
    $("#list-sub-contract-tb").dataTable();
    var init = function () {
      captureNewGrant();
      datePicker();
      grantUpdateModal();
      deleteGrantModal();
      downloadGrantsData();
      viewGrantDetails();
      uploadFiles();
      saveSubContractDetails();
      subContractUpdateModal();
      editUploadFiles();
    };
    var captureNewGrant = function () {
      $(".save-grant").click(function () {
        if ($("#form-new-grant").valid()) {
          var form = $("#form-new-grant").closest("form");
          var formData = new FormData(form[0]);
          var button = $(this);
          button.attr("disabled", true);
          button.html(" Saving " + '<i class="fa fa-spinner fa-spin"> </i>');

          $.ajax({
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            url: kanzuMaksph.ajaxUrl,
            success: function (response) {
              if (response.success) {
                location.reload();
              } else {
                button.html("Try Again");
              }
            },
          });
        } else {
          $(".error").focus();
        }
      });
    };

    var saveSubContractDetails = function () {
      $(".save-sub-contract").click(function () {
        if ($("#form-grant-subcontract").valid()) {
          var form = $("#form-grant-subcontract").closest("form");
          var formData = new FormData(form[0]);
          var button = $(this);
          button.html("Saving...");

          $.ajax({
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            url: kanzuMaksph.ajaxUrl,
            success: function (response) {
              if (response.success) {
                //Get ID of upload form Data CPT
                let subcontractID = response.data.id;

                var fileInputSubcontract = $(".file-subcontract");
                var fileInputDiligence = $(".file-due-diligence");

                var subcontractKey = fileInputSubcontract.attr("id");
                var dueDiligenceKey = fileInputDiligence.attr("id");

                var subtractTitle = fileInputSubcontract.data("title");
                var dueDiligenceTitle = fileInputDiligence.data("title");

                var fileSubcontractor = fileInputSubcontract.prop("files")[0];
                var fileDiligence = fileInputDiligence.prop("files")[0];

                var data = new FormData();
                data.append("file_subcontract", fileSubcontractor);
                data.append("file_diligence", fileDiligence);
                data.append("action", "kc_maksph_upload_files");
                data.append("subcontract_id", subcontractID);
                data.append("title_subcontract", subtractTitle);
                data.append("title_diligence", dueDiligenceTitle);
                data.append("key_subcontract", subcontractKey);
                data.append("key_diligence", dueDiligenceKey);

                $.ajax({
                  type: "POST",
                  url: kanzuMaksph.ajaxUrl,
                  enctype: "multipart/form-data",
                  data: data,
                  processData: false,
                  contentType: false,
                  cache: false,
                  success: function (response) {
                    location.reload();
                  },
                });
              } else {
                button.html("Try Again");
              }
            },
          });
        } else {
          $(".error").focus();
        }
      });
    };

    var datePicker = function () {
      $(".startdate-input").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 480 + "px",
          });
        },
      });
      $("#edit-gend-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 680 + "px",
          });
        },
      });

      $("#edit-issue-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 380 + "px",
          });
        },
      });

      //
      $("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 280 + "px",
          });
        },
      });

      $("#gend-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 680 + "px",
          });
        },
      });

      $("#issue-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 350 + "px",
            marginLeft: $this.offset().left - 380 + "px",
          });
        },
      });

      $("#sub-issue-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 380 + "px",
          });
        },
      });

      $("#sub-start-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 530 + "px",
          });
        },
      });

      $("#sub-end-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 640 + "px",
          });
        },
      });

      //
      $("#sub-edit-issue-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 380 + "px",
          });
        },
      });

      $("#sub-edit-start-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 530 + "px",
          });
        },
      });

      $("#sub-edit-end-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        beforeShow: function (input, inst) {
          var $this = $(this);
          inst.dpDiv.css({
            marginTop: $this.offset().top - 550 + "px",
            marginLeft: $this.offset().left - 640 + "px",
          });
        },
      });
    };

    var grantUpdateModal = function () {
      $(".btn-edit-grant").on("click", function () {
        let salesData = JSON.parse($(this).data("gdata"));
        let myModal = jQuery("#edit-grants-details");
        $("#sales-id").val(salesData.id);
        $("#u-client-name").val(salesData.client_name);
        $("#u-telephone").val(salesData.client_telephone);
        $("#u-quantity").val(salesData.quantity);
        $("#u-particular").val(salesData.particular);
        $("#u-rate").val(salesData.rate);
        $("#u-amount").val(salesData.amount_paid);

        myModal.modal("show");

        //Update Details
        $(".update-grant").click(function () {
          if ($("#form-update-grant").valid()) {
            var form = $("#form-update-grant").closest("form");
            var formData = new FormData(form[0]);
            var button = $(this);
            button.attr("disabled", true);
            button.html(
              " Updating " + '<i class="fa fa-spinner fa-spin"> </i>'
            );

            $.ajax({
              type: "POST",
              data: formData,
              dataType: "json",
              processData: false,
              contentType: false,
              url: kanzuMaksph.ajaxUrl,
              success: function (response) {
                if (response.success) {
                  location.reload();
                  return false;
                } else {
                  button.html("Try Again");
                }
              },
            });
          } else {
            $(".error").focus();
          }
        });
      });
    };

    var subContractUpdateModal = function () {
      $(".btn-edit-subcontract").on("click", function () {
        let subContractData = JSON.parse($(this).data("gdata"));
        let myModal = jQuery("#edit-subcontract-details");
        $("#subcontract-id").val(subContractData.id);
        $("#institution").val(subContractData.institution);
        $("#principal").val(subContractData.principal);
        $("#edit-country").val(subContractData.source_country);
        $("#sub-edit-issue-date").val(subContractData._issue_date);
        $("#sub-edit-start-date").val(subContractData._start_date);
        $("#sub-edit-end-date").val(subContractData._end_date);
        $("#fund_amount").val(subContractData._fund_amount);
        $("#funder").val(subContractData._funder);
        $("#edit-currency").val(subContractData._fund_currency);

        if (subContractData.sub_contract.file_name !== "") {
        }
        get_file_upload_data(
          $(".contact-file-upload"),
          subContractData.sub_contract.file_name,
          subContractData.sub_contract.url
        );
        if (subContractData.due_deligence_form.file_name !== "") {
          get_file_upload_data(
            $(".diligence-file-upload"),
            subContractData.due_deligence_form.file_name,
            subContractData.due_deligence_form.url
          );
        }

        myModal.modal("show");

        //Update Details
        $(".update-subcontract").click(function () {
          var form = $("#form-update-subcontract").closest("form");
          var formData = new FormData(form[0]);
          var button = $(this);
          button.html("Updating...");

          $.ajax({
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            url: kanzuMaksph.ajaxUrl,
            success: function (response) {
              if (response.success) {
                location.reload();
                return false;
              } else {
                button.html("Try Again");
              }
            },
          });
        });
      });
    };

    var deleteGrantModal = function () {
      $(".btn-delete-grant").on("click", function () {
        let grantID = $(this).data("id");
        let myModal = jQuery("#deleteGrantModal");

        myModal.modal("show");

        //Update Details
        $(".delete-grant").click(function () {
          var button = $(this);
          button.html("Deleting...");

          $.ajax({
            type: "POST",
            data: { grant_id: grantID, action: "delete_grant_details" },
            dataType: "json",
            url: kanzuMaksph.ajaxUrl,
            success: function (response) {
              if (response.success) {
                location.reload();
                return false;
              } else {
                button.html("Try Again");
              }
            },
          });
        });
      });
    };

    var downloadGrantsData = function () {
      $(".download-excel").on("click", function () {
        $.ajax({
          type: "POST",
          data: {
            action: "kc_download_grants_data",
          },
          url: kanzuMaksph.ajaxUrl,
          success: function (response) {
            window.location.href = response.data;
          },
        });
      });
    };

    var viewGrantDetails = function () {
      $("#view-grant-detail").on("click", function () {
        let grantID = $(this).data("gid");
        console.log(grantID);
        $.ajax({
          type: "POST",
          data: {
            grantID: grantID,
            action: "kc_get_grant_details",
          },
          url: kanzuMaksph.ajaxUrl,
          success: function (response) {
            window.location.href;
          },
        });
      });
    };

    //Validate file uploads
    var uploadFiles = function () {
      $(".file-uploads")
        .off("input")
        .on("input", function (e) {
          var fileInput = $(this);
          if (this.files[0].size > 5 * 1024 * 1024) {
            alert("File size should not exceed 5MB!");
            fileInput.val("");
          } else {
            var title = fileInput.data("title");
            fileInput.parent().attr("data-text", title);
            fileInput.parent().addClass("valid");
          }
        });
    };

    var editUploadFiles = function () {
      $(".edit-file-uploads")
        .off("input")
        .on("input", function (e) {
          var fileInput = $(this);
          if (this.files[0].size > 5 * 1024 * 1024) {
            alert("File size should not exceed 5MB!");
            fileInput.val("");
          } else {
            var key = fileInput.attr("id");
            var title = fileInput.data("title");
            var subcontract_id = $("#subcontract-id").val();

            var file = fileInput.prop("files")[0];
            var data = new FormData();
            data.append("file", file);
            data.append("action", "kc_maksph_upload_files");
            data.append("subcontract_id", subcontract_id);
            data.append("title", title);
            data.append("key", key);
            $.ajax({
              type: "POST",
              url: kanzuMaksph.ajaxUrl,
              enctype: "multipart/form-data",
              data: data,
              processData: false,
              contentType: false,
              cache: false,
              success: function (response) {
                fileInput
                  .parent()
                  .attr("data-text", response["data"][0]["file_name"]);
                fileInput.parent().addClass("valid");
                fileInput
                  .parent()
                  .siblings("small")
                  .find("a")
                  .attr("href", response["data"][0]["url"]);
                fileInput
                  .parent()
                  .siblings("small")
                  .find("a")
                  .attr("target", "_blank");
              },
            });
          }
        });
    };

    init();
  });
})(jQuery);

function get_file_upload_data(fileInput, displayText, fileUrl) {
  fileInput.parent().attr("data-text", displayText);
  fileInput.parent().addClass("valid");
  fileInput.parent().siblings("small").find("a").attr("href", fileUrl);
  fileInput.parent().siblings("small").find("a").attr("target", "_blank");
}
