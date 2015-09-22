$(document).on("ready",btn_click);

function btn_click()
{
	$(".btn-primary").hover(function(){
		$(".btn-primary").val("Ingresar");
	},$(".btn-primary").mouseleave(function(){
		$(".btn-primary").val("Entrar");
	}));
	$(".btn-primary").click(function(){
		$(".btn-primary").addClass("circulo");
		$(".btn-primary").removeClass("button");
		$(".btn-primary").addClass("button2");
		$(".btn-primary").val("Ok");
	}),	$(".btn-primary").hover(function(){
		$(".btn-primary").val("OK");
	},1000);
}
