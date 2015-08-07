$(document).on("ready",btn_click);

function btn_click()
{
	$(".btn-info").hover(function(){
		$(".btn-info").val("Ingresar");
	},$(".btn-info").mouseleave(function(){
		$(".btn-info").val("Entrar");
	}));
	$(".btn-info").click(function(){
		$(".btn-info").addClass("circulo");
		$(".btn-info").removeClass("button");
		$(".btn-info").addClass("button2");
		$(".btn-info").val("Ok");
	}),	$(".btn-info").hover(function(){
		$(".btn-info").val("OK");
	},1000);
}
