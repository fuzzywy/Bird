$(function () {

	//设置中英文切换
	setLocaleLang = function(type) {
		$.ajax({
			type: "get",
			url: "localeLang",
			data: {"lang":type},
			// async: true,
			success: function (returnData) {
				location.reload();
			}
		});
	}
})