(function ($) {
  "use strict";

  const EasyEL = {
    init: function () {
      this.cacheDOM();
      this.bindEvents();
    },

    cacheDOM: function () {
      this.$modal = $("#easyel-template-modal");
      this.$openBtn = $("#easyel-theme-builder-add-template");
      this.$closeBtn = $(".easyel-close, .easyel-cancel-btn");
      this.$addRowBtn = $("#easyel-add-condition");
      this.$conditionsWrapper = $("#easyel-conditions-wrapper");
      this.$saveBtn = $(".easyel-save-btn");
      this.$editBtn = $(".easyel-edit-template");
    },

    bindEvents: function () {
      const self = this;

      // Open modal
      self.$openBtn.on("click.easyel", function (e) {
        e.preventDefault();
        self.$modal.fadeIn();

        self.$modal.css({
          visibility: "visible",
          opacity: "1",
        });
      });

      // Close modal
      self.$closeBtn.on("click.easyel", function () {
        self.$modal.fadeOut();
      });

      // Add new condition row
      self.$addRowBtn.on("click.easyel", function () {
        self.addConditionRow();

      });

      $(document).on("click.easyel", ".easyel-remove-row", function () {
      
        let $wrapper = $(this).closest("#easyel-conditions-wrapper, .easyel-conditions-wrapper-edit");

        let $row  = $(this).closest(".easyel-condition-row-edit, .easyel-condition-row");
        let $rows = $wrapper.children(".easyel-condition-row-edit, .easyel-condition-row");

        if ($rows.length > 1 && !$row.is($rows.first())) {
            $row.remove();
        }
    });

      // Close success modal
      $(document).on(
        "click.easyel",
        "#easyel-success-modal .easyel-close, #easyel-success-modal .easyel-cancel-btn",
        function () {
          $("#easyel-success-modal").fadeOut();
          location.reload();
        }
      );

      // Save & Close (AJAX)
      self.$saveBtn.on("click.easyel", function () {
        self.saveConditions();
      });

      self.$editBtn.on("click.easyel", function () {
        self.updateConditions();
      });
    },

    addConditionRow: function () {
      // Decide which wrapper to use
      const $wrapper =  this.$conditionsWrapper;

      // Clone first row
      const $row = $wrapper.find(".easyel-condition-row").first().clone();

      $row.find("select.easyel-include-type").val("include");
      $row.find("select.easyel-condition-main").val("entire-site");
      $row.find("select.easyel-condition-sub").empty();

      if ($row.find("select.easyel-condition-main").val() === "entire-site") {
          $row.find("select.easyel-condition-sub").hide();
      } else {
          $row.find("select.easyel-condition-sub").show();
      }

      // Append to wrapper
      $wrapper.append($row);
    },

    saveConditions: function () {
      const conditions = [];
      this.$conditionsWrapper.find(".easyel-condition-row").each(function () {
        conditions.push({
          include: $(this).find(".easyel-include-type").val(),
          main: $(this).find(".easyel-condition-main").val(),
          sub: $(this).find(".easyel-condition-sub").val(),
        });
      });

      let easyTmplType = $(".easyel-builder-tmpl-type").val();
      let easyTmplName = $(".easyel-builder-template-name").val();

      $.ajax({
        url: easyel_builder_obj.ajax_url,
        type: "POST",
        data: {
          action: "easyel_save_template_conditions",
          conditions: conditions,
          template_type: easyTmplType,
          template_name: easyTmplName,
          nonce: easyel_builder_obj.nonce,
        },
        success: function (response) {
          if (response.success) {
            $("#easyel-edit-template").attr("href", response.data.edit_url);
            $("#easyel-template-modal").fadeIn().css({
              visibility: "hidden",
              opacity: "0",
            });

            $("#easyel-success-modal").fadeIn().css({
              visibility: "visible",
              opacity: "1",
            });
          } else {
            $(".easyel-template-error-message").html(response.data.message);
          }
        },
        error: function () {
          alert("Something went wrong!");
        },
      });
    },

    updateConditions: function () {
      let $modal = $(".easyel-edit-template-condition");
      let post_id = $modal.attr("data-post-id");
      let template_name = $modal.find(".easyel-builder-template-name").val();
      let template_type = $modal.find(".easyel-builder-tmpl-type").val();

      let conditions = [];

      $modal
        .find(".easyel-conditions-wrapper-edit .easyel-condition-row-edit")
        .each(function () {
          let $row = $(this);
          let include = $row.find(".easyel-include-type").val();
          let main = $row.find(".easyel-condition-main").val();
          let sub = $row.find(".easyel-condition-sub").val();

          conditions.push({
            include: include,
            main: main,
            sub: sub,
          });
        });

      $.ajax({
        url: easyel_builder_obj.ajax_url,
        type: "POST",
        data: {
          action: "easyel_update_builder",
          nonce: easyel_builder_obj.nonce,
          post_id: post_id,
          template_name: template_name,
          template_type: template_type,
          conditions: conditions,
        },
        success: function (res) {
          if (res.success) {
            $modal.fadeOut();
            location.reload();
          } else {
            $(".easyel-template-error-message").html(res.data.message);
          }
        },
      });
    },
  };

  $(document).ready(function () {
    EasyEL.init();
  });

  $(document).ready(function ($) {

    function populateSubOptions($mainSelect, selectedSub = "") {
      var $row = $mainSelect.closest(
        ".easyel-condition-row,.easyel-condition-row-edit"
      );
      var $subSelect = $row.find(".easyel-condition-sub");

      var mainValue = $mainSelect.val();
      $subSelect.empty();

      if (mainValue === "entire-site") {
        $subSelect.hide();
      } else if (mainValue === "archives") {
        $subSelect.show();

        $.ajax({
          url: easyel_builder_obj.ajax_url,
          method: "POST",
          data: { action: "easyel_get_archives" },
          success: function (response) {
            if (response.success) {
              let data = response.data;

              function createOption(item) {
                return $("<option>", {
                  value: item.value,
                  text: item.label,
                  selected: item.value === selectedSub ? true : false,
                });
              }

              if (data.core) {
                let $group = $("<optgroup>", { label: "Core Archives" });
                data.core.forEach((item) => $group.append(createOption(item)));
                $subSelect.append($group);
              }
              if (data.posts_archive) {
                let $group = $("<optgroup>", { label: "Posts Archive" });
                data.posts_archive.forEach((item) =>
                  $group.append(createOption(item))
                );
                $subSelect.append($group);
              }
              if (data.products_archive) {
                let $group = $("<optgroup>", { label: "Products Archive" });
                data.products_archive.forEach((item) =>
                  $group.append(createOption(item))
                );
                $subSelect.append($group);
              }
            }
          },
        });
      } else if (mainValue === "singular") {
        $subSelect.show();

        $.ajax({
          url: easyel_builder_obj.ajax_url,
          method: "POST",
          data: { action: "easyel_get_singulars" },
          success: function (response) {
            if (response.success && Array.isArray(response.data)) {
              response.data.forEach(function (item) {
                if (item.group) {
                  let $group = $subSelect.find(
                    'optgroup[label="' + item.group + '"]'
                  );
                  if (!$group.length) {
                    $group = $("<optgroup>", { label: item.group });
                    $subSelect.append($group);
                  }
                  $group.append(
                    $("<option>", {
                      value: item.value,
                      text: item.label,
                      selected: item.value === selectedSub,
                    })
                  );
                } else {
                  $subSelect.append(
                    $("<option>", {
                      value: item.value,
                      text: item.label,
                      selected: item.value === selectedSub,
                    })
                  );
                }
              });
            }
          },
        });
      }
    }

    // trigger on page load for existing rows
    $(
      ".easyel-condition-row .easyel-condition-main,.easyel-condition-row-edit .easyel-condition-main"
    ).each(function () {
      populateSubOptions($(this));
    });

    // trigger on change
    $(document).on("change", ".easyel-condition-main", function () {
      populateSubOptions($(this));
    });

    function easyelUpdateEditWithElementorUrl() {
      const post_id = $("#easyel-template-modal-edit").attr("data-post-id");

      if (post_id) {
        const editUrl = new URL(
          easyel_builder_obj.admin_url + "post.php",
          window.location.origin
        );
        editUrl.searchParams.set("post", post_id);
        editUrl.searchParams.set("action", "elementor");

        $("#easyel-edit-with-elementor").attr("href", editUrl.toString());
      }
    }

    // Modal open & load data
    $(".type-easy_theme_builder .row-actions .edit a").on(
      "click",
      function (e) {
        e.preventDefault();

        let post_id = $(this).closest("tr").attr("id").replace("post-", "");

        $(".easyel-edit-template-condition").attr("data-post-id", post_id);

        easyelUpdateEditWithElementorUrl();
        

        // AJAX Call
        $.ajax({
          url: easyel_builder_obj.ajax_url,
          type: "POST",
          data: {
            action: "easyel_get_builder",
            nonce: easyel_builder_obj.nonce,
            post_id: post_id,
          },
          success: function (res) {
            if (res.success) {
              let data = res.data;
              console.log(data);

              // Fill modal fields
              $(".easyel-builder-template-name").val(data.template_name);
              $(".easyel-builder-tmpl-type").val(data.template_type);

              const $wrapper = $("#easyel-conditions-wrapper-edit");
              // Reset old conditions
              $wrapper.empty();

              if (data.conditions.length) {
                data.conditions.forEach(function (cond) {
                  let $row = $(easyel_render_condition_row(cond));
                  $wrapper.append($row);

                  populateSubOptions($(".easyel-condition-main", $row), cond.sub || 'all');

                });
              } else {
                let $row = $(easyel_render_condition_row());
                $wrapper.append($row);
                populateSubOptions($(".easyel-condition-main", $row), 'all');
              }

              // $wrapper.find(".easyel-condition-row-edit").each(function () {
               
              //   const $main = $(this).find(".easyel-condition-main");
              //    console.log( $main.val() );
              //   const $sub = $(this).find(".easyel-condition-sub");
              //   if ( $main.val() === "entire-site" ) {
              //       $sub.hide();
              //   } else {
              //       $sub.show();
              //   }
              // });

              $(".easyel-edit-template-condition").fadeIn().css({
                visibility: "visible",
                opacity: "1",
              });
            } else {
              alert(res.data.message);
            }
          },
        });
      }
    );

    // Close modal
    $(document).on("click", ".easyel-close, .easyel-cancel-btn", function () {
      $(".easyel-edit-template-condition").fadeOut();
      location.reload();
    });

    $(document).on("click", ".easyel-edit-template-condition", function (e) {
      if ($(e.target).is(".easyel-edit-template-condition")) {
        $(".easyel-edit-template-condition").fadeOut();
      }
    });

    $(document).on("click", "#easyel-add-condition-edit", function (e) {
      e.preventDefault();
      $(".easyel-conditions-wrapper-edit").append(
        easyel_render_condition_row()
      );
    });

    function easyel_render_condition_row(cond = {}) {
      let include = cond.include || "include";
      let main = cond.main || "entire-site";

      return `
        <div class="easyel-condition-row-edit">
            <select class="easyel-include-type">
                <option value="include" ${
                  include == "include" ? "selected" : ""
                }>Include</option>
                <option value="exclude" ${
                  include == "exclude" ? "selected" : ""
                }>Exclude</option>
            </select>
            <select class="easyel-condition-main">
                <option value="entire-site" ${
                  main == "entire-site" ? "selected" : ""
                }>Entire Site</option>
                <option value="archives" ${
                  main == "archives" ? "selected" : ""
                }>Archives</option>
                <option value="singular" ${
                  main == "singular" ? "selected" : ""
                }>Singular</option>
            </select>
            <select class="easyel-condition-sub">
               
            </select>
            <span class="easyel-remove-row">&times;</span>
        </div>`;
    }
  });

})(jQuery);
