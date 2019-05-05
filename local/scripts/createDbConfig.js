window.addEventListener("load", function()
{
	const formDbConfig = document.querySelector("#formDbConfig");
	formDbConfig.addEventListener("submit", async function(e)
	{
		e.preventDefault();
		const response = await fetch("createDbConfig.php", {method: "POST", body: new FormData(this)});
		const responseData = await response.text();
		
		if(responseData === "true")
		{
			console.log("Utworzono połączenie z bazą danych."); // implementacja prostego systemu powiadomień
			setTimeout(function()
			{
				location = "addProxy.php";
			}, 2000);
		}
		else
		{
			console.error("Error: " + responseData); // implementacja prostego systemu powiadomień
		}
	});
});