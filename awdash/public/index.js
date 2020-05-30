$(document).ready(function() {

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1;
var yyyy = today.getFullYear();
var defaultdate;

if(dd<10)
{
    dd='0'+dd;
}
if(mm<10)
{
    mm='0'+mm;
}

function getAll(time = "01012013" + dd + mm + yyyy, website = "")
{
//console.log("time= " + time + "website = " + website);
$("#table-body").css("visibility","hidden");
$("#loading").css("display","block");

var url = "https://Domain/websites/" + time + "/" + website;
$.get(url, function(data, status){
    var rows = "";
//    console.log(data);
    for(var i=0; i<data.length; i++)
    {
    rows += "<tr>" +
    "<th scope='row'>" + data[i].sno +  "</th>" +
    "<td><a href='http://" + data[i].website  +  "' target='_blank'>" + data[i].website + "</a></td>" +
    "<td>" + data[i].unique_visit + "</td>" +
    "<td>" + data[i].total_visit + "</td>" +
    "<td>" + data[i].total_page_loads + "</td>" +
    "<td>" + data[i].total_hits + "</td>" +
    "<td>" + data[i].total_bandwidth + "</td>" +
    "<td><a href='" + data[i].awstats + "' target='_blank'>Click Here</a></td>" +
  "</tr>";
    }

    $("#table-body").html(rows);
    $("#loading").css("display","none");
    $("#table-body").css("visibility","visible");

  });
}

$("#filter").click(e => {
    e.preventDefault();
    let website = $("#myselect").val();
    let time = ($("input")[0].value).split("-")[2] + ($("input")[0].value).split("-")[1] + ($("input")[0].value).split("-")[0] +
               ($("input")[1].value).split("-")[1] + ($("input")[1].value).split("-")[1] + ($("input")[1].value).split("-")[0];

    if(time.includes("undefined"))
    time = null;

    if(time && website)
     getAll(time, website);
    else if(time)
     getAll(time);
    else
     getAll(defaultdate+dd+mm+yyyy, website);
 });

$("#reset").click(e => {
    e.preventDefault();
    getAll();
 });

$.getJSON("sites.json", function(json) {
    defaultdate = json['defaultdate'];
    $.each(json['sites'], (index, value) => {
      val = parseInt(index, 10) + 1;
      $("#myselect").append("<option value='" + val + "'>" + value['domain'] + "</option>");
 });
 getAll();
});
});
