
var kvFields = [];

function updateJetVars() {
	try {
		var foundblank = false;
		var newvars = {};
		for (var i in kvFields) {
			if (kvFields[i].key.value!="") {
				newvars[kvFields[i].key.value] = kvFields[i].value.value
			} else {
				foundblank = true;
			}
		}
		if (!foundblank) {
			drawJetVarRow("", "");
		}
		console.log(newvars);
		jetvarsjson.value = JSON.stringify(newvars);
	} catch(er) {
		console.log(er);
	}
}

function drawJetVarRow(k, v, b) {
	var kvField = {};
	var tr = document.createElement("tr");
	kvField.row = tr;
	var td = document.createElement("td");
	var a = document.createElement("input");
	a.type = "text";
	a.value = k;
	a._key = k;
	a._value = v;
	kvField.key = a;
	td.appendChild(a);
	if (!!b) { a.focus(); }
	a.addEventListener("blur", updateJetVars);
	tr.appendChild(td);
	var td = document.createElement("td");
	var b = document.createElement("input");
	b.type = "text";
	b.value = v;
	b._key = k;
	b._value = v;
	kvField.value = b;
	td.appendChild(b);
	b.addEventListener("blur", updateJetVars);
	tr.appendChild(td);
	jetvars.appendChild(tr);
	kvFields.push(kvField);
}

function drawJetVars(jvs) {
	jetvars.innerHTML = "";
	kvFields = [];
	for (var jv in jvs) {
		drawJetVarRow(jv, jvs[jv]);
	}
	drawJetVarRow("", "", true);
	
}

var jetvarsjson = document.getElementById('jet_vars_json');
var jetvars = document.getElementById('jet_vars');
drawJetVars(JETVARS);


