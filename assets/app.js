import 'bootstrap';
import './bootstrap.js';
import "https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js";
//import "https://kit.fontawesome.com/4008f9efc5.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

//console.log("chartjs",agCharts.AgCharts.create(options));
const url = location.href;
const path = "/change-examined";
const statPath = "/stats-medecin";
const listPatientPath = "/api/v1/medecin/{id}/patients";
const tabelId = "visitorTableBody";
const formatedUrl = (url, stringToReplace, newString) => {
  return url.replace(stringToReplace, newString);
}
const createTdElement = (text) => {
  const td = document.createElement("td");
  td.innerText = text;
  return td;
}
const createTheTableRow = (item, index) => {
  const tr = document.createElement("tr");
  const date = new Date(item.arrivalDate);
  tr.appendChild(createTdElement(item.arrivalPosition));
  tr.appendChild(createTdElement(item.patient.nom));
  tr.appendChild(createTdElement(item.patient.prenom));
  tr.appendChild(createTdElement(item.patient.email));
  tr.appendChild(createTdElement(item.patient.socialSecurityNumber));
  //tr.appendChild(createTdElement(new Date(item.arrivalDate).toLocaleDateString()));
  tr.appendChild(createTdElement(date.toLocaleTimeString()));
  const btnTd = document.createElement("td");
  const btn = document.createElement("button");
  btn.id = `visitor${index}-` + item.id;
  btn.classList.add("btn");
  btn.classList.add("rounded");
  btn.classList.add("btn-small");
  btn.title = "not yet";
  btn.classList.add("btn-warning");
  btn.innerHTML = '<i class="bi bi-hand-thumbs-down"></i>';
  btn.addEventListener('click', (e) => {
    let tagName = e.target.tagName;
    const pointer = tagName === "I" ? e.target.parentElement : e.target;
    if (btn.title.includes("not yet")) {
      const visitorId = pointer.id.split("-")[1];
      putExaminedOnTrue({
        id: visitorId
      }, btn);
      e.target.innerHTML = '<i class="bi bi-hand-thumbs-up"></i>';
      e.target.classList.remove("btn-warning");
      e.target.classList.add("btn-success");
    }
  }, false)
  btnTd.appendChild(btn);
  tr.appendChild(btnTd);
  return tr;
}
const refreshPatientList = (id) => {
  const apiUrl = formatedUrl(listPatientPath, "{id}", id);
  fetch(apiUrl)
    .then(response => response.json())
    .then((result) => {
      const tableBody = document.getElementById(tabelId);
      tableBody.innerHTML = "";
      result.map((item, index) => {
        const tr = createTheTableRow(item, index);
        tableBody.appendChild(tr);
      })
    });
}
const formatDataToChart = (data) => {
  return data.map((item) => {
    item.arrivalDate = item.arrivalDate.split("-")[0]
    return item;
  })
}

const formatDataTransform = (data) => {
  return data.map((item) => {
    item.arrivalDate = item.arrivalDate.split("T")[0]
    return item;
  })
}
const itemExistByValue = (data, item, prop = "arrivalDate") => {
  if (data.length === 0) return false;
  for (let i = 0; i < data.length; i++) {
    if (data[i][`${prop}`] === item[`${prop}`]) return true
  }
  return false;
}
const transformDataToPatientByDate = (data) => {
  const newDataPatient = [];
  for (let i = 0; i < data.length; i++) {
    const item = {
      numberOfPatient: 1,
      arrivalDate: data[i].arrivalDate
    };
    for (let j = 0; j < data.length && j != i; j++) {
      if (item.arrivalDate === data[j].arrivalDate) {
        item["numberOfPatient"] += 1;
      }
    }
    let value = false;
    for (let k = 0; k < newDataPatient.length; k++) {
      if (newDataPatient[k].arrivalDate === item.arrivalDate) {
        value = true;
        if (newDataPatient[k].numberOfPatient < item.numberOfPatient)
          newDataPatient[k].numberOfPatient = item.numberOfPatient;
        break;
      }
    }
    if (value === false)
      newDataPatient.push(item);

  }
  return newDataPatient;
}

const getMedecinData = (id, blocker) => {
  let options = {};
  fetch(statPath + "/" + id, {
      method: "POST", // *GET, POST, PUT, DELETE, etc.
      // mode: "cors", // no-cors, *cors, same-origin
      //cache: "reload", // *default, no-cache, reload, force-cache, only-if-cached
      //credentials: "same-origin", // include, *same-origin, omit
      headers: {
        "Content-Type": "application/json",
      },
      body: "",
    }).then(response => response.json())
    .then((result) => {
      //console.log("tranformat:", transformDataToPatientByDate(formatDataTransform(result)));
      result = transformDataToPatientByDate(formatDataTransform(result));
      //console.log("resultat:", result);
      options = {
        container: blocker, // Container: HTML Element to hold the chart
        // Chart Title
        title: {
          text: "stats sur le visite"
        },
        // Chart Subtitle
        subtitle: {
          text: "Data from " + (new Date().getFullYear())
        },

        data: result,
        // Series: Defines which chart type and data to use
        series: [{
            type: "bar",
            yKey: "numberOfPatient",
            xKey: "arrivalDate",
            xName: "Date d'Arrivée",
            yName: "Nombre de Patients"
          },
          /*{
            type: "line",
            yKey: "examined",
            xKey: "arrivalDate",
            yName: "cas examiné",
          },*/
        ],
        // Axes: Configure the axes for the chart
        axes: [
          // Display category (xKey) as the bottom axis
          {
            type: "date",
            position: "bottom",
            keys: ["arrivalDate"],
            label: "Années"
          },
          // Use left axis for 'iceCreamSales' series
          {
            type: "number",
            position: "left",
            keys: ["numberOfPatient"],
            // Format the label applied to this axis
            /*label: {
              formatter: (params) => {
                return parseFloat(params.value).toLocaleString();
              },
            },*/
          },
          // Use right axis for 'avgTemp' series
          {
            type: "number",
            position: "right",
            keys: ["arrivalDate"],
            // Format the label applied to this axis (append ' °C')
            label: {
              formatter: (params) => {
                return params.value + " °C";
              },
            },
          },
        ],
        // Legend: Matches visual elements to their corresponding series or data categories.
        legend: {
          position: "right",
        },
      };
      agCharts.AgCharts.create(options)

    })
}
const putExaminedOnTrue = (data, btn) => {
  fetch(path, {
      method: "POST", // *GET, POST, PUT, DELETE, etc.
      mode: "no-cors", // no-cors, *cors, same-origin
      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
      //credentials: "same-origin", // include, *same-origin, omit
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    }).then(response => response.json())
    .then((result) => {
      if (result.status === "success") {
        btn.title = "Yes";
        btn.classList.remove("btn-warning");
        btn.classList.add("rounded");
        btn.classList.add("btn-success");
        btn.innerHTML = '<i class="bi bi-hand-thumbs-up"></i>';
      }

    })
}


if (url.includes("/stats")) {

  console.log("je suis sur la page /stats");
  const container = document.querySelector("#stats");
  const id = parseInt(document.querySelector("#stats").getAttribute("data-user"));
  getMedecinData(id, container);

}
if (url.includes("/list-patient")) {
  console.log("je suis sur la page list-patient");
  const medecinId = parseInt(document.querySelector("#medecinId").getAttribute("value"));
  setInterval(() => {
    refreshPatientList(medecinId);
  }, 10000);
}