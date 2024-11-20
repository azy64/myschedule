import 'bootstrap';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
const url = location.href;
const path="/change-examined";

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
