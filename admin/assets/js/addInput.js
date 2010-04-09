
var arrInput = new Array(0);
var arrInputValue = new Array(0);

function addInput() {
  arrInput.push(arrInput.length);
  arrInputValue.push("");
  display();
}

function display() {
  document.getElementById('parah').innerHTML="";
  for (intI=0;intI<arrInput.length;intI++) {
    document.getElementById('parah').innerHTML+=createInput(arrInput[intI], arrInputValue[intI]);
  }
}

function saveValue(intId,strValue) {
  arrInputValue[intId]=strValue;
}

function createInput(id,value) {
  return "<input type='text' name='title[]' style='width: 200px; margin-bottom: 5px;' id='category "+ id +"' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"'><br>";
}

function deleteInput() {
  if (arrInput.length > 0) {
     arrInput.pop();
     arrInputValue.pop();
  }
  display();
}



