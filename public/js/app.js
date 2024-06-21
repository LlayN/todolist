const removeButton = document.querySelectorAll(".removeButton");
const modifyButton = document.querySelectorAll(".modifyButton");
const sortButton = document.querySelectorAll('[name="sort"]');
const filterButton = document.querySelectorAll('[name="filter"]');

export function dataView(d, selected = false) {
  const dataView = document.querySelector("#dataView");
  if (selected) {
    let elementArray = Array.from(dataView.childNodes);
    elementArray.forEach((element) => {
      dataView.removeChild(element);
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
    for (let i = 0; i < 2; i++) {
      let div = document.createElement("div");
      div.className = "dropdown mx-3";
      div.id = i;
      div2.append(div);

      var button = document.createElement("button");
      button.className = "btn btn-secondary btn-data";
      button.type = "button";
      button.setAttribute("data-bs-toggle", "dropdown");
      button.setAttribute("aria-expanded", "false");

      var ul = document.createElement("ul");
      ul.className = "dropdown-menu";

      for (let i = 0; i < 2; i++) {
        let li = document.createElement("li");
        let a = document.createElement("a");
        a.id = i;
        if (a.id == 0) {
          a.className = "dropdown-item";
          a.href = "#";
          a.textContent = "Modifier";
        } else {
          a.className = "dropdown-item";
          a.href = "#";
          a.textContent = "Supprimer";
        }
        li.append(a);
        ul.append(li);
      }
    }

    dataView.append(div2);
    dataView.lastElementChild.firstElementChild.append(div1, newTitle);
    dataView.lastElementChild.lastElementChild.append(button);
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
    body: "update=updateState" + "&id=" + id + "&state=" + newState,
  })
    .then((response) => {
      console.log(response.ok);
    })

    .catch((e) => {
      console.log("ERREUR : " + e.message);
    });
  return newState;
}

removeButton.forEach((element) => {
  element.addEventListener("click", () => {
    let itemId = element.getAttribute("id");
    fetch("controllers/Controllers.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id=" + itemId,
    })
      .then((response) => {
        console.log(response.ok);
        location.reload();
      })

      .catch((error) => {
        console.error("ERREUR", error);
      });
  });
});

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
