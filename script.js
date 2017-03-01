function $id(name)
{
	return document.getElementById(name);
}

function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function f_xhr() {
  if (typeof XMLHttpRequest === 'undefined') {
	XMLHttpRequest = function() {
	  try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
		catch(e) {}
	  try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
		catch(e) {}
	  try { return new ActiveXObject("Msxml2.XMLHTTP"); }
		catch(e) {}
	  try { return new ActiveXObject("Microsoft.XMLHTTP"); }
		catch(e) {}
	  throw new Error("This browser does not support XMLHttpRequest.");
	};
  }
  return new XMLHttpRequest();
}

function f_popup(text)
{
	alert(text);
	return false;
}

function f_getlist(id, val)
{
	var xhr = f_xhr();
	if(xhr)
	{
		xhr.open("get", "req.php?action=categories", true);
		xhr.onreadystatechange = function(e) {
			if(this.readyState == 4) {
				if(this.status == 200)
				{
					var result = JSON.parse(this.responseText);
					if(result.result)
					{
						f_popup("Error: "+ result.status);
					}
					else
					{
						var el = $id('col'+id+'_3');
						var i;
						for(i = 0; i < result.list.length; i++)
						{
							var option = document.createElement("option");
							option.textContent = result.list[i].name;
							option.value = result.list[i].id;
							el.add(option);
							if(result.list[i].name == val)
							{
								el.selectedIndex = i+1;
							}
						}
					}
				}
				else
				{
					f_popup("Error: HTTP " + this.status + " " + this.statusText);
				}
			}
		};
		xhr.send(null);
	}
}

function f_save(id)
{
	var name = $id('col'+id+'_2').value.trim();
	var cid = parseInt($id('col'+id+'_3').value, 10);
	var price = $id('col'+id+'_4').value;
	
	if(name.length == 0)
	{
		return f_popup("Invalid name");
	}
	
	if(cid <= 0)
	{
		return f_popup("Select category");
	}

	if(!/^\d+(\.\d{1,2})?$/.test(price))
	{
		return f_popup("Invalid price");
	}

	var xhr = f_xhr();
	if(xhr)
	{
		xhr.open("post", "req.php?action="+(id?"edit&id="+id:"add"), true);
		xhr.onreadystatechange = function(e) {
			if(this.readyState == 4) {
				if(this.status == 200)
				{
					var result = JSON.parse(this.responseText);
					if(result.result)
					{
						f_popup("Error: " + result.status);
					}
					else
					{
						var row = $id("row"+id);
						row.id = "row" + result.id;

						row.cells[0].textContent = result.id;
						row.cells[1].textContent = result.name;
						row.cells[2].textContent = result.category;
						row.cells[3].textContent = result.price;
						row.cells[4].innerHTML = '<a href="#" onclick="f_edit('+result.id+'); return false;">Edit</a>&nbsp;<a href="#" onclick="f_delete('+result.id+'); return false;">Delete</a>';
					}
				}
				else
				{
					f_popup("Error: HTTP " + this.status + " " + this.statusText);
				}
			}
		};
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send("name="+encodeURIComponent(name)+"&cid="+encodeURIComponent(cid)+"&price="+encodeURIComponent(price));
	}
	
	return false;
}

function f_edit(id)
{
	var row = null;
	if(id == 0)
	{
		if(!$id('row0'))
		{
			row = $id("table").insertRow(-1);
			row.id = "row0";
			row.insertCell(0);
			row.insertCell(1);
			row.insertCell(2);
			row.insertCell(3);
			row.insertCell(4);
			row.cells[1].textContent = "New item";
		}
	}
	else
	{
		row = $id('row'+id);
	}

	if(row)
	{
		row.cells[1].innerHTML = '<input id="col'+id+'_2" type="text" style="width: 98%" value="'+escapeHtml(row.cells[1].textContent)+'"/>';
		val = row.cells[2].textContent;
		row.cells[2].innerHTML = '<select id="col'+id+'_3"><option value=0>Выберите категорию</option></select>';
		row.cells[3].innerHTML = '<input id="col'+id+'_4" type="text" style="width: 98%" value="'+escapeHtml(row.cells[3].textContent)+'"/>';
		row.cells[4].innerHTML = '<input type="button" value="Save" onclick="f_save('+id+'); return false;"/>';

		var el = $id('col'+id+'_2');
		el.setSelectionRange(0, el.value.length);
		el.focus();
		f_getlist(id, val);
	}
	return false;
}

function f_delete(id)
{
	var xhr = f_xhr();
	if(xhr)
	{
		xhr.open("get", "req.php?action=delete&id="+id, true);
		xhr.onreadystatechange = function(e) {
			if(this.readyState == 4) {
				if(this.status == 200)
				{
					var result = JSON.parse(this.responseText);
					if(result.result)
					{
						f_popup("Error: "+ result.status);
					}
					else
					{
						var row = $id("row"+id);
						row.parentNode.removeChild(row);
					}
				}
				else
				{
					f_popup("Error: HTTP " + this.status + " " + this.statusText);
				}
			}
		};
		xhr.send(null);
	}
}

