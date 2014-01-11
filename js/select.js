// JavaScript Document

function selAll(){
	l=document.getElementsByTagName("INPUT");
	for(i=0;i<l.length;i++) 
	{ 
	if(l[i].type=="checkbox") {
		l[i].checked=true;
		}
	} 
}

function noSelAll(){
	l=document.getElementsByTagName("INPUT");
	for(i=0;i<l.length;i++) 
	{ 
		if(l[i].type=="checkbox") {
			l[i].checked=false;
		}
	} 
}

function delArc(result1){
	var result=result1+"?delete=";
	var row=0;
	var flag="";
	l=document.getElementsByTagName("INPUT")
	for(i=0;i<l.length;i++) 
	{ 
		if(l[i].type=="checkbox" && l[i].checked) {
			flag+=l[i].value+".";
		}
	}
	if(flag.length==0){
		alert("请先选择要删除记录");
	}else{
		window.location.href=result+flag;
	}
}
