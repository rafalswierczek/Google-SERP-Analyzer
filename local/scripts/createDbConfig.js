const scriptPath = document.currentScript.src;
const currentDir = scriptPath.substring(0, scriptPath.lastIndexOf('/'))+"/";

window.addEventListener("load", function()
{
	const formDbConfig = document.querySelector("#formDbConfig");
	formDbConfig.addEventListener("submit", async function(e)
	{
		e.preventDefault();
		const response = await fetch(currentDir+"../ajax/createDbConfig.php", {method: "POST", body: new FormData(this)});
		const responseData = await response.json();
		
		if(responseData['type'] === "result")
		{
			console.log(responseData['body']); // implementacja prostego systemu powiadomień
			setTimeout(function()
			{
				location = "addProxy.php";
			}, 2500);
		}
		else if(responseData['type'] === "error")
		{
			console.error(responseData['body']); // implementacja prostego systemu powiadomień
		}
		else
			console.error(responseData);
	});
});