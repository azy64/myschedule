import 'bootstrap';
import './bootstrap.js';
import "https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js";
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

const getMedecinData =(id, blocker)=>{
    let options={};
    fetch(statPath+"/"+id,{
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
        "Content-Type": "application/json",
        },
        body:"",
    }).then(response=>response.json())
    .then((result)=>{
        console.log("resultat:",result);
        options = {
            container: blocker, // Container: HTML Element to hold the chart
            // Chart Title
            title: { text: "stats sur le visite" },
            // Chart Subtitle
            subtitle: { text: "Data from 2024" },
            
            data: result,
            // Series: Defines which chart type and data to use
            series: [
              {
                type: "bar",
                xKey: "examined",
                yKey: "arrivalDate",
                yName: "Date d'Arrivée",
              },
              {
                type: "line",
                xKey: "examined",
                yKey: "arrivalDate",
                yName: "cas examiné",
              },
            ],
            // Axes: Configure the axes for the chart
            axes: [
              // Display category (xKey) as the bottom axis
              {
                type: "category",
                position: "bottom",
              },
              // Use left axis for 'iceCreamSales' series
              {
                type: "number",
                position: "left",
                keys: ["arrivalDate"],
                // Format the label applied to this axis
                label: {
                  formatter: (params) => {
                    return parseFloat(params.value).toLocaleString();
                  },
                },
              },
              // Use right axis for 'avgTemp' series
              {
                type: "number",
                position: "right",
                keys: ["examined"],
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
            const visitorId =e.target.id.split("-")[1];
            putExaminedOnTrue({id:visitorId},btn);
            console.log("click",visitorId);
        })
    })
}
const putExaminedOnTrue =(data,btn)=>{
    fetch(path,{
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
        "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    }).then(response=>response.json())
    .then((result)=>{
        console.log("resultat:",result);
        if(result.status==="success"){
            btn.classList.remove("btn-warning");
            btn.classList.add("btn-success");
            btn.innerHTML="YES";
        }
        
    })
}

