/**
 * ScreenLook
 */
function screenLock(){
  var element = document.createElement('div');
  element.id = "screenLock";
  
  var objBody = document.getElementsByTagName("body").item(0);
  objBody.appendChild(element);
}
  
/**
 * ScreenUnLook
 */
function screenUnLock(){
  var screenLock = document.getElementById("screenLock");
  if(screenLock){
    screenLock.parentNode.removeChild(screenLock);
  }
}