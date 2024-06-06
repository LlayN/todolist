const removeButton = document.querySelectorAll(".removeButton");

removeButton.forEach((element) => {
  element.addEventListener("click", () => {
    let itemId = element.getAttribute("id");
    fetch("controllers/Controllers.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded", //
      },
      body: "id=" + itemId,
    })
      .then((response) => {
        location.reload();
      })

      .catch((error) => {
        console.error("ERREUR", error);
      });
  });
});
