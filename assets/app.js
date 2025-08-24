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
const path="/change-examined";
const statPath="/stats-medecin";
const refresh=()=>{
  //console.log("refresh...");
  window.location.reload();
  
}
const formatDataToChart=(data)=>{
  return data.map((item)=>{
    item.arrivalDate= item.arrivalDate.split("-")[0]
    return item;
  })
}

const formatDataTransform=(data)=>{
  return data.map((item)=>{
    item.arrivalDate= item.arrivalDate.split("T")[0]
    return item;
  })
}

const getMedecinData =(id, blocker)=>{
    let options={};
    fetch(statPath+"/"+id,{
        method: "POST", // *GET, POST, PUT, DELETE, etc.
       // mode: "cors", // no-cors, *cors, same-origin
        //cache: "reload", // *default, no-cache, reload, force-cache, only-if-cached
        //credentials: "same-origin", // include, *same-origin, omit
        headers: {
        "Content-Type": "application/json",
        },
        body:"",
    }).then(response=>response.json())
    .then((result)=>{
        result= formatDataTransform(result);
        console.log("resultat:",result);
        options = {
            container: blocker, // Container: HTML Element to hold the chart
            // Chart Title
            title: { text: "stats sur le visite" },
            // Chart Subtitle
            subtitle: { text: "Data from "+(new Date().getFullYear()) },
            
            data: result,
            // Series: Defines which chart type and data to use
            series: [
              {
                type: "bar",
                yKey: "id",
                xKey: "arrivalDate",
                xName: "Date d'Arrivée",
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
                keys:["arrivalDate"],
                label:"Années"
              },
              // Use left axis for 'iceCreamSales' series
              {
                type: "number",
                position: "left",
                keys: ["examined"],
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
const putExaminedOnTrue =(data,btn)=>{
  fetch(path,{
      method: "POST", // *GET, POST, PUT, DELETE, etc.
      mode: "no-cors", // no-cors, *cors, same-origin
      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
      //credentials: "same-origin", // include, *same-origin, omit
      headers: {
      "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
  }).then(response=>response.json())
  .then((result)=>{
      console.log("resultat:",result);
      if(result.status==="success"){
          btn.title="Yes";
          btn.classList.remove("btn-warning");
          btn.classList.add("rounded");
          btn.classList.add("btn-success");
          btn.innerHTML='<i class="bi bi-hand-thumbs-up"></i>';
      }
      
  })
}


if(url.includes("/stats")){
    
    console.log("je suis sur la page /stats");
    const container = document.querySelector("#stats");
    const id = parseInt(document.querySelector("#stats").getAttribute("data-user"));
    getMedecinData(id, container);
    
}
if(url.includes("/list-patient")){
    
    console.log("je suis sur la page list-patient");
    const btns = document.querySelectorAll("button[id^=visitor]");
    btns.forEach((btn)=>{
        btn.addEventListener('click',(e)=>{
          //console.log('btn::',  e.target);
          let tagName = e.target.tagName;
          const pointer = tagName==="I"?e.target.parentElement:e.target;
          if(btn.title.includes("not yet")){
            const visitorId =pointer.id.split("-")[1];
            putExaminedOnTrue({id:visitorId},btn);
          }
          else console.log("le bouton est vert");
            
        },false)
    })
    setInterval(refresh,10000);
}


