const scriptPath = document.currentScript.src;
const currentDir = scriptPath.substring(0, scriptPath.lastIndexOf('/'))+"/";

fetchAnalyses();
addAnalysis();

async function addAnalysis()
{
    const addModal = document.querySelector("#addModal");
    const addButton = document.querySelector("#addAnalysis");
    const addAnalysisForm = document.querySelector("#addAnalysisForm");

    window.addEventListener("click", e=>
    {
        if(e.target === addModal)
        {
            addModal.style.display = "none";
        }
    });

    addButton.addEventListener("click", function()
    {
        addModal.style.display = "flex";
    });

    addAnalysisForm.addEventListener("submit", async function(e)
    {
        e.preventDefault();
        addModal.style.display = "none";

        const formData = new FormData(this);
        const response = await fetch(currentDir+"../ajax/addAnalyses.php", {method: "POST", body: formData});
        const responseData = await response.json();

        if(responseData['type'] === "result")
        {
                const analyses = document.querySelector("#analyses");
                while (analyses.lastChild)
                    analyses.lastChild.remove();
                fetchAnalyses();

                console.log(responseData['body']); // implementacja prostego systemu powiadomień
        }
        else if(responseData['type'] === "error")
        {
            console.error(responseData['body']); // implementacja prostego systemu powiadomień
        }
        else
            console.error("Error: " + responseData);
    });
}

async function fetchAnalyses()
{
    const response = await fetch(currentDir+"../ajax/fetchAnalyses.php?fetch=true", {method: "GET"});
    const responseData = await response.json();
    
    if(responseData['type'] === "result")
    {
        if(responseData['body'].length > 0)
        {
            responseData['body'].forEach((row, index) =>
            {
                const  analysis = createAnalisis(row);
                document.querySelector("#analyses").appendChild(analysis);

                analysis.addEventListener("click", async function()
                {
                    let id = analysis.querySelector(".analysisId").innerHTML;
                    let formData = new FormData();
                    formData.append('analysis_id', id);
                    const response = await fetch(currentDir+"../ajax/getChartData.php", {
                        method: 'POST',
                        body: formData
                    });
                    const responseData = await response.json();

                    if(typeof responseData === "string")
                    {
                        let chart = document.getElementById("chart");
                        chart.style.marginTop = "50px";
                        chart.style.textAlign = "center";
                        chart.innerHTML = responseData;
                    }
                    else
                        newChart(analysis.querySelector(".domain").innerHTML, responseData, responseData[0][0], responseData[responseData.length-1][0]);
                });
            });
        }
        else
            document.querySelector("#analyses").innerHTML = "Brak analiz";
    }
    else if(responseData['type'] === "error")
    {
        console.error(responseData['body']); // implementacja prostego systemu powiadomień
    }
    else
        console.error("Error: " + responseData);
}

function createAnalisis(row)
{
    const analysis = document.createElement("div"); analysis.className = "analysis";
    const analysisId = document.createElement("div"); analysisId.className = "analysisId";
    const domain = document.createElement("div"); domain.className = "domain";
    const phrase = document.createElement("div"); phrase.className = "phrase";
    const deleteAnalysis = document.createElement("button"); deleteAnalysis.className = "deleteAnalysis";
    analysis.appendChild(analysisId);
    analysis.appendChild(domain);
    analysis.appendChild(phrase);
    analysis.appendChild(deleteAnalysis);
    analysisId.innerHTML = row.analysis_id;
    domain.innerHTML = row.domain;
    phrase.innerHTML = row.phrase;
    deleteAnalysis.innerHTML = "X";

    const infoBox = document.querySelector("#infoBox");
    const domainInfo = document.querySelector("#domainInfo");
    const phraseInfo = document.querySelector("#phraseInfo");
    let mouseDelay;
    const displayInfoBox = function()
    {
        mouseDelay = setTimeout(()=>
        {
            infoBox.style.display = "flex";
            domainInfo.innerHTML = row.domain;
            phraseInfo.innerHTML = row.phrase;
        }, 300);
    };
    const closeInfoBox = function()
    {
        clearTimeout(mouseDelay);
        infoBox.style.display = "none";
    };

    domain.addEventListener("mouseover", displayInfoBox);
    domain.addEventListener("mouseout", closeInfoBox);
    phrase.addEventListener("mouseover", displayInfoBox);
    phrase.addEventListener("mouseout", closeInfoBox);
    deleteAnalysis.addEventListener("click", function()
    {
        fetch(currentDir+`../ajax/deleteAnalysis.php?id=${row.analysis_id}`, {method: "GET"})
        .then(response=>
        {
            return response.json();
        })
        .then(responseData=>
        {
            if(responseData['type'] === "result")
            {
                const analyses = document.querySelector("#analyses");
                while (analyses.lastChild)
                    analyses.lastChild.remove();
                fetchAnalyses();

                console.log(responseData['body']); // implementacja prostego systemu powiadomień
            }
            else if(responseData['type'] === "error")
            {
                console.error(responseData['body']); // implementacja prostego systemu powiadomień
            }
            else
                console.error("Error: " + responseData);
        });
    });
    
    return analysis;
}

function newChart(name, values, dataLimitFrom, dataLimitTo, from=null, to=null)
{
    var t = new TimeChart(
    {
        container: document.getElementById("chart"),

        data:[
        {
            id: "data",
            units:["m"],
            timestampInSeconds: true,
            preloaded: {
                unit: "m",
                values: values,
                dataLimitFrom: dataLimitFrom,
                dataLimitTo: dataLimitTo,
                from: from,
                to: to
            }
        }],

        timeAxis: {
            timeZone: "Europe/Warsaw",
        },

        series:[
        {
            name: name,
            type:"line",
            data:
            {
                source: "data",
                index:1,
                aggregation:"avg"
            },
            style:
            {
                smoothing: true,
                lineColor: "#3F7598",
                lineWidth: 1,
                marker:
                {
                    fillColor: "#2A4C62",
                    shape: "circle",
                    width: 8
                }
            }
        }],

        toolbar:
        {
            location: "outside",
            fullscreen: true,
            items: [
                {
                    label: "export",
                    align: "right",
                    cssClass: "DVSL-bar-btn-export",
                    dropDownItems: [
                        {
                            label: "Obraz (PNG)",
                            onClick: function () { t.export("png"); }
                        },
                        {
                            label: "Obraz (JPG)",
                            onClick: function () { t.export("jpg"); }
                        },
                        {
                            label: "Dokument (PDF)",
                            onClick: function () { t.export("pdf"); }
                        },
                        {
                            label: "Excel (XLSX)",
                            onClick: function () { t.export("xlsx"); }
                        }
                    ]
                },
                { item: "fullscreen", align: "right" }
            ]
        },

        valueAxisDefault:
        {
            scaleStep: 1,
            style:
            {
                title: { textStyle: { font: "15px consolas"}, align: "center", margin: 0 },
                valueLabel: { textStyle: { font: "15px consolas", fillColor: "#2A4C62"}}
            },
            title:"Pozycja"
        },

        area:
        {
            minHeight: 400
        },

        interaction:
        {
            scrolling:
            {
                kineticFriction: 0.00001
            },
            selection:
            {
                enabled: false
            },
            zooming:
            {
                swipe: false,
                click: false,
                wheel: true,
                wheelSensitivity: 1.2,
            }
        }
    });
}