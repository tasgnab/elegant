$.fn.filter = function(input,list){
	var value = $(input).val();
	if (value === ""){
		$(list+" > li").show()
	} else {
		$(list+" > li:not(:containsIgnoreCase("+value+"))").hide();
	}
} 

$.fn.addSelection = function(id){
	var tmp = id.split("-");
	var li = '<li id="' + id +'" rel="'+$("#"+id).attr('rel')+'" class="search-choice" title="' + $('#' + id).text() + '">';
		li += '<span>' + $('#' + id).text() + '</span>';
		li += '<a class="search_choice_close" onClick="$.fn.removeSelection(\''+id+'\')" href="javascript:void(0);"></a>';
		li += '</li>';
	$('#'+id).remove();
	$(li).appendTo('#'+tmp[0]+'-selection');
	$.fn.hideList(tmp[0]);
	$("#"+tmp[0]+"-input").val("");
	$("#"+tmp[0]+"-list > li").show();
}

$.fn.addSelectionSingle = function(id){
	var tmp = id.split("-");
	var list = tmp[0]+"-list";
	var selection = tmp[0]+"-selection";
	$("#"+selection+" li").each(function(index){
		if (index>0){
			var tmpLi = '<li id="' + $(this).attr('id') +'" rel="'+$(this).attr('rel')+'" onClick="$.fn.addSelectionSingle(\''+$(this).attr('id')+'\')">';
				tmpLi += $(this).text();
				tmpLi += '</li>';

			var lastIndex = $('#'+list+' li').length;
			var rel = parseInt($(this).attr('rel'));

			$(this).remove();
			if(lastIndex < rel){
				$(tmpLi).insertAt(lastIndex, $("#"+list));
			}else{
				$(tmpLi).insertAt(rel, $("#"+list));
			}
		}
		
	$(li).appendTo('#'+tmp[0]+'-list');
	});
	var li = '<li id="' + id +'" rel="'+$("#"+id).attr('rel')+'" class="search-choice" title="' + $('#' + id).text() + '">';
		li += '<span>' + $('#' + id).text() + '</span>';
		li += '<a class="search_choice_close" onClick="$.fn.removeSelectionSingle(\''+id+'\')" href="javascript:void(0);"></a>';
		li += '</li>';
	$('#'+id).remove();
	$(li).appendTo('#'+tmp[0]+'-selection');
	$.fn.hideList(tmp[0]);
	$("#"+tmp[0]+"-input").val("");
	$("#"+tmp[0]+"-list > li").show();
}

$.fn.removeSelection = function(id){
	var list = id.split("-")[0]+"-list";
	var li = '<li id="' + id +'" rel="'+$('#'+id).attr('rel')+'" onClick="$.fn.addSelection(\''+id+'\')">';
		li += $('#' + id + ' span').text();
		li += '</li>';

		var lastIndex = $('#'+list+' li').length;
		var rel = parseInt($('#'+id).attr('rel'));

		$('#'+id).remove();

	if(lastIndex < rel){
		$(li).insertAt(lastIndex, $("#"+list));
	}else{
		$(li).insertAt(rel, $("#"+list));
	}
}

$.fn.removeSelectionSingle = function(id){
	var list = id.split("-")[0]+"-list";
	var li = '<li id="' + id +'" rel="'+$('#'+id).attr('rel')+'" onClick="$.fn.addSelectionSingle(\''+id+'\')">';
		li += $('#' + id + ' span').text();
		li += '</li>';

		var lastIndex = $('#'+list+' li').length;
		var rel = parseInt($('#'+id).attr('rel'));

		$('#'+id).remove();

	if(lastIndex < rel){
		$(li).insertAt(lastIndex, $("#"+list));
	}else{
		$(li).insertAt(rel, $("#"+list));
	}
}