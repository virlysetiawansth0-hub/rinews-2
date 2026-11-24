jQuery(document).ready(function ($) {
  $(document).on("click", ".js-rn-upload-btn", function (e) {
    e.preventDefault();
    if (typeof wp === "undefined" || !wp.media) {
      return;
    }
    var button = $(this);
    var targetSelector = button.data("target");
    var frame = wp.media({
      title: "Pilih atau Upload Gambar Iklan RINEWS.ID",
      button: { text: "Gunakan Gambar Ini" },
      multiple: false
    });
    frame.on("select", function () {
      var attachment = frame.state().get("selection").first().toJSON();
      $(targetSelector).val(attachment.url).trigger("change");
    });
    frame.open();
  });
});
