const removeButton = document.querySelectorAll(".removeButton");
const modifyButton = document.querySelectorAll(".modifyButton");
const sortButton = document.querySelectorAll('[name="sort"]');
const filterButton = document.querySelectorAll('[name="filter"]');

export function dataView(d, data_manip) {
  const dataView = document.querySelector("#dataView");

  if (data_manip) {
    Array.from(dataView.childNodes).forEach((element) => {
      element.remove();
    });
  }

  for (let i = 0; i < d.length; i++) {
    let dateNow = new Date();
    let yesterday = lastDay();
    let tomorrow = nextDay();

    let newTitle = document.createElement("h6");
    let firstLetter =
      d[i]["titre"].charAt(0).toUpperCase() + d[i]["titre"].substring(1);
    newTitle.textContent = firstLetter;
    newTitle.className = "title";

    let newDateTime = document.createElement("p");

    var div1 = document.createElement("div");
    div1.className = "datetime d-flex gap-1";
    div1.append(newDateTime, newTitle);

    let dateTache = new Date(`${d[i]["date_tache"]}T${d[i]["heure_tache"]}`);

    d[i]["etat"] = verifyState(dateNow, dateTache, d[i]["etat"], d[i]["id"]);

    switch (dateTache.toLocaleDateString("fr-FR")) {
      case dateNow.toLocaleDateString("fr-FR"):
        d[i]["date_tache"] =
          newDateTime.textContent = `Aujourd'hui , à ${dateTache.toLocaleTimeString(
            "fr-FR",
            {
              hour: "2-digit",
              minute: "2-digit",
            }
          )}`;
        break;

      case yesterday.toLocaleDateString("fr-FR"):
        d[i]["date_tache"] =
          newDateTime.textContent = `Hier , à ${dateTache.toLocaleTimeString(
            "fr-FR",
            {
              hour: "2-digit",
              minute: "2-digit",
            }
          )}`;
        break;

      case tomorrow.toLocaleDateString("fr-FR"):
        d[i]["date_tache"] =
          newDateTime.textContent = `Demain , à ${dateTache.toLocaleTimeString(
            "fr-FR",
            {
              hour: "2-digit",
              minute: "2-digit",
            }
          )}`;
        break;

      default:
        newDateTime.textContent =
          dateTache.toLocaleDateString("fr-FR") +
          " , à " +
          dateTache.toLocaleTimeString("fr-FR", {
            hour: "2-digit",
            minute: "2-digit",
          });
    }

    var div2 = document.createElement("div");
    div2.className = "data d-flex justify-content-between align-items-center";
    div2.id = i;
    var idTask = d[i]["id"];

    for (let i = 0; i < 2; i++) {
      let div = document.createElement("div");
      div.className = "dropdown mx-3";
      div2.append(div);

      var buttonBubble = document.createElement("button");
      buttonBubble.id = i;
      buttonBubble.className = "btn btn-secondary btn-data";
      buttonBubble.type = "buttonBubble";
      buttonBubble.setAttribute("data-bs-toggle", "dropdown");
      buttonBubble.setAttribute("aria-expanded", "false");

      var ul = document.createElement("ul");
      ul.className = "dropdown-menu";

      for (let i = 0; i < 2; i++) {
        let li = document.createElement("li");
        let a = document.createElement("a");
        var btn = document.createElement("button");
        btn.id = idTask;
        btn.addEventListener("click", function () {
          let divId = this.closest(".data");
          divId.remove();
          removeTask(this.id, dataView);
        });

        a.id = i;
        if (a.id == 0) {
          a.className = "dropdown-item";
          a.textContent = "Modifier";
          a.id = idTask;
          a.addEventListener("click", function () {
            modifyTask(this.id);
          });
          a.href = "?page=modifier_tache";
          li.append(a);
        } else {
          btn.className = "dropdown-item";

          btn.textContent = "Supprimer";
          li.append(btn);
        }

        ul.append(li);
      }
    }

    dataView.append(div2);
    dataView.lastElementChild.firstElementChild.append(div1, newTitle);
    dataView.lastElementChild.lastElementChild.append(buttonBubble);
    dataView.lastElementChild.lastElementChild.append(ul);
  }
}

function nextDay() {
  let newDate = new Date();
  newDate.setDate(newDate.getDate() + 1);
  return newDate;
}

function lastDay() {
  let newDate = new Date();
  newDate.setDate(newDate.getDate() - 1);
  return newDate;
}

function verifyState(dateN, dateT, etat, id) {
  let newState = "";
  let formateDateNow = dateN.toLocaleDateString("fr-FR");
  let formateDateTask = dateT.toLocaleDateString("fr-FR");
  if (formateDateNow == formateDateTask) {
    newState = "aujourd'hui";
  } else if (formateDateNow > formateDateTask) {
    newState = "en retard";
  } else if (formateDateNow < formateDateTask) {
    newState = "à venir";
  }
  fetch("controllers/Controllers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "update_state=update" + "&id=" + id + "&state=" + newState,
  })
    .then((response) => {
      console.log(response.ok);
    })

    .catch((e) => {
      console.log("ERREUR : " + e.message);
    });
  return newState;
}

function modifyTask(id) {
  fetch("controllers/Controllers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "id_modify=" + id,
  })
    .then((response) => {
      return response.text();
    })
    .then((data) => {
      console.log(data);
    })
    .catch((e) => {
      console.log("ERREUR : " + e.message);
    });
}

function removeTask(id, dataView) {
  fetch("controllers/Controllers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "remove=" + id,
  })
    .then((response) => {
      console.log(response.ok);
      if (dataView.childNodes.length == 1) {
        window.location.reload();
      }
    })

    .catch((error) => {
      console.error("ERREUR", error);
    });
}

sortButton.forEach((element) => {
  element.addEventListener("change", () => {
    let value = element.value;
    // Action dans HomeManager.php
    fetch("controllers/Controllers.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "action=" + value,
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        dataView(data, true);
      })
      .catch((error) => {
        console.log("ERREUR", error);
      });
  });
});

filterButton.forEach((element) => {
  element.addEventListener("change", () => {
    let value = element.value;
    // Action dans HomeManager.php
    fetch("controllers/Controllers.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "action=" + value,
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        dataView(data, true);
      })
      .catch((error) => {
        console.log("ERREUR", error);
      });
  });
});
