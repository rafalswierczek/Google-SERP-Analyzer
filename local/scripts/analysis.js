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

                analysis.addEventListener("click", function()
                {
                    // tworzy wykres
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