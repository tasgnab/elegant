$.expr[':'].containsIgnoreCase = function (n, i, m) {
	return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
}

$.fn.showList = function(id) {
	$('#'+id+'-list').removeClass('hide');
		$('#'+id+'-list').addClass('show');
}

$.fn.hideList = function(id) {
	$('#'+id+'-list').removeClass('show');
	$('#'+id+'-list').addClass('hide');
}

$.fn.insertAt = function(index, $parent) {
	return this.each(function() {
		if (index === 0) {
			$parent.prepend(this);
		} else {
			$parent.children().eq(index - 1).after(this);
		}
	});
}