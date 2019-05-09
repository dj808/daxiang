/**
 * jquery.area.js
 * 移动端省市区三级联动选择插件
 * author: 锐不可挡
 * date: 2016-06-17
**/


var province = 	["国内进港", "国内出港", "国际进港", "国际出港"];
var jc = 	["CI", "CO", "II", "IO"];

var expressArea, areaCont, areaList = $("#areaList"), areaTop = areaList.offset().top;

/*初始化省份*/
function intProvince() {
	areaCont = "";
	for (var i=0; i<province.length; i++) {
		areaCont += '<li onClick="selectP(' + i + ');">' + province[i] + '</li>';
	}
	areaList.html(areaCont);
	$("#areaBox").scrollTop(0);
	$("#backUp").removeAttr("onClick").hide();
}
intProvince();

/*选择省份*/
function selectP(p) {
	areaCont = "";
	areaList.html("");
	
	areaList.html(areaCont);	
	clockArea();
	expressArea = province[p];
	tureArea = jc[p];
	$(".kuaidi input").val(expressArea);
	$("#ty").val(tureArea);
	
}




/*关闭省市区选项*/
function clockArea() {
	$("#areaMask").fadeOut();
	$("#areaLayer").animate({"bottom": "-100%"});
	intProvince();
}

$(function() {
	/*打开省市区选项*/
	$("#expressArea").click(function() {
		$("#areaMask").fadeIn();
		$("#areaLayer").animate({"bottom": 0});
	});
	/*关闭省市区选项*/
	$("#areaMask, #closeArea").click(function() {
		clockArea();
	});
});