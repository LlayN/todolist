const removeButton = document.querySelector(".removeButton");

removeButton.addEventListener("click", () => {
  fetch("controllers/Controllers.php").then((response) => {
    console.log(response);
  });
});
