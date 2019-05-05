window.addEventListener("load", function()
{
	const formProxy = document.querySelector("#formProxy");
	formProxy.addEventListener("submit", async function(e)
	{
		e.preventDefault();
		const response = await fetch("addProxiesToDb.php", {method: "POST", body: new FormData(this)});
		const responseData = await response.text();
		
		if(responseData === "true")
		{
			console.log("Zapisano adresy proxy"); // implementacja prostego systemu powiadomień
			// body.innerHTML += niszczy event 'submit'
		}
		else
		{
			console.log("Error: " + responseData); // implementacja prostego systemu powiadomień
		}
	});

	const clearInputs = document.querySelectorAll(".clear");
	clearInputs.forEach(elem =>
	{
		elem.addEventListener("click", function()
		{
			formProxy.querySelector("#"+this.dataset.clear+"Ip").value = "";
			formProxy.querySelector("#"+this.dataset.clear+"Port").value = "";
		});
	});
});