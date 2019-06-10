const scriptPath = document.currentScript.src;
const currentDir = scriptPath.substring(0, scriptPath.lastIndexOf('/'))+"/";

window.addEventListener("load", function()
{
	function processInputs(status, proxy_id = null)
	{
		const deleteInputs = document.querySelectorAll(".clear");
		const editInputs = document.querySelectorAll(".edit");
		const formProxy = document.querySelector("#formProxy");


		for(let i = 1; i <= 10; i++)
		{
			const proxyInput = formProxy.querySelectorAll(`input[name^='proxy[${i-1}]']`);
			if(proxyInput[2].value.length > 0 && proxyInput[3].value.length > 0 && proxyInput[4].value.length > 0 && proxyInput[5].value.length > 0)
			{
				if(!proxy_id)
				{
					if(status === "saved")
					{
						proxyInput[2].className = "proxyInput saved"; proxyInput[2].disabled = true;
						proxyInput[3].className = "proxyInput saved"; proxyInput[3].disabled = true;
						proxyInput[4].className = "proxyInput saved"; proxyInput[4].disabled = true;
						proxyInput[5].className = "proxyInput saved"; proxyInput[5].disabled = true;
					}
					else if(status === "error")
					{
						proxyInput[2].className = "proxyInput error"; proxyInput[2].disabled = false;
						proxyInput[3].className = "proxyInput error"; proxyInput[3].disabled = false;
						proxyInput[4].className = "proxyInput error"; proxyInput[4].disabled = false;
						proxyInput[5].className = "proxyInput error"; proxyInput[5].disabled = false;
					}
				}
				else
				{
					if(i === proxy_id)
					{	
						proxyInput[2].className = "proxyInput error"; proxyInput[2].disabled = false;
						proxyInput[3].className = "proxyInput error"; proxyInput[3].disabled = false;
						proxyInput[4].className = "proxyInput error"; proxyInput[4].disabled = false;
						proxyInput[5].className = "proxyInput error"; proxyInput[5].disabled = false;
					}
				}
			}
		}


		formProxy.addEventListener("submit", async function(e)
		{
			e.preventDefault();
			for(let i = 1; i <= 10; i++) // .disabled = false; => ajax body !empty
			{
				const proxyInput = formProxy.querySelectorAll(`input[name^='proxy[${i-1}]']`);
				if(proxyInput[2].value.length > 0 && proxyInput[3].value.length > 0 && proxyInput[4].value.length > 0 && proxyInput[5].value.length > 0)
				{
					proxyInput[2].disabled = false;
					proxyInput[3].disabled = false;
					proxyInput[4].disabled = false;
					proxyInput[5].disabled = false;
				}
			}
			let response = await fetch(currentDir+"../ajax/addProxiesToDb.php", {method: "POST", body: new FormData(this)});
			let responseData = await response.json();
			
			if(responseData['type'] === "result")
			{
				console.log(responseData['body']); // implementacja prostego systemu powiadomień

				response = await fetch(currentDir+"../views/config/loadProxies.php", {method: "POST"});
				responseData = await response.text();

				const proxies = document.querySelector("#proxies");
				while (proxies.lastChild)
					proxies.lastChild.remove();
				proxies.innerHTML = responseData;

				processInputs("saved");
			}
			else if(responseData['type'] === "error")
			{
				const proxy_id = Number(responseData['proxy_id']);
				processInputs("error", proxy_id);
				console.error(responseData['body']); // implementacja prostego systemu powiadomień
				setTimeout(()=>{
					location.reload();
				}, 2500);
			}
			else
				console.error(responseData);
		});

		deleteInputs.forEach(elem =>
		{
			elem.addEventListener("click", function()
			{
				const proxyId = this.dataset.proxy.substr(5);
				const proxyInput = formProxy.querySelectorAll(`input[name^='proxy[${proxyId-1}]']`);
				proxyInput[0].value = "delete";
				proxyInput[2].className = "proxyInput cleared";
				proxyInput[3].className = "proxyInput cleared";
				proxyInput[4].className = "proxyInput cleared";
				proxyInput[5].className = "proxyInput cleared";
			});
		});
	
		editInputs.forEach(elem =>
		{
			elem.addEventListener("click", function()
			{
				const proxyId = this.dataset.proxy.substr(5);
				const proxyInput = formProxy.querySelectorAll(`input[name^='proxy[${proxyId-1}]']`);
	
				proxyInput[2].className = "proxyInput edit"; proxyInput[2].disabled = false;
				proxyInput[3].className = "proxyInput edit"; proxyInput[3].disabled = false;
				proxyInput[4].className = "proxyInput edit"; proxyInput[4].disabled = false;
				proxyInput[5].className = "proxyInput edit"; proxyInput[5].disabled = false;
			});
		});
	}
	processInputs("saved");
});