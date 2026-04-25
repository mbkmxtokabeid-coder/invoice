document.addEventListener("DOMContentLoaded", function () {
  // Get all elements with the class "clickable-row"
  var clickableRows = document.querySelectorAll(".clickable-row");

  // Add click event listeners to each row
  clickableRows.forEach(function (row) {
    row.addEventListener("click", function () {
      // Get the "data-href" attribute value of the clicked row
      var href = this.getAttribute("data-href");

      // Navigate to the URL specified in the "data-href" attribute
      if (href) {
        window.location.href = href;
      }
    });
  });
});